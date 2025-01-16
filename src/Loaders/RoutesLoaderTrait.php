<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Nucleus\Foundation\Facades\Nuclear;
use Symfony\Component\Finder\SplFileInfo;

trait RoutesLoaderTrait
{
    /**
     * Register all the containers routes files in the framework
     */
    public function runRoutesAutoLoader(): void
    {
        $containersPaths = Nuclear::getAllContainerPaths();

        foreach ($containersPaths as $containerPath) {
            $this->loadApiContainerRoutes($containerPath);
            $this->loadWebContainerRoutes($containerPath);
        }
    }

    /**
     * Register the Containers API routes files
     *
     * @param string $container_path
     */
    private function loadApiContainerRoutes(string $container_path): void
    {
        // Build the container api routes path
        $apiRoutesPath = $container_path . '/UI/API/Routes';

        if (File::isDirectory($apiRoutesPath)) {
            $files = File::allFiles($apiRoutesPath);
            $files = Arr::sort($files, static fn($file) => $file->getFilename());

            foreach ($files as $file) {
                $this->loadApiRoute($file);
            }
        }
    }

    /**
     * @param $file
     */
    private function loadApiRoute($file): void
    {
        $routeGroupArray = $this->getRouteGroup($file);

        Route::group($routeGroupArray, function ($router) use ($file) {
            require $file->getPathname();
        });
    }

    /**
     * @param      $endpointFileOrPrefixString
     * @return  array
     */
    public function getRouteGroup($endpointFileOrPrefixString): array
    {
        return [
            'middleware' => $this->getMiddlewares(),
            'domain' => $this->getApiUrl(),
            'prefix' => \is_string(
                $endpointFileOrPrefixString
            ) ? $endpointFileOrPrefixString : $this->getApiVersionPrefix($endpointFileOrPrefixString),
        ];
    }

    /**
     * @return  array
     */
    private function getMiddlewares(): array
    {
        return array_filter([
            'api',
            $this->getRateLimitMiddleware(), // Returns NULL if feature disabled. Null will be removed form the array.
        ]);
    }

    /**
     * @return  null|string
     */
    private function getRateLimitMiddleware(): ?string
    {
        $rateLimitMiddleware = null;

        if (Config::get('nucleus.api.throttle.enabled')) {
            RateLimiter::for('api', static function (Request $request) {
                return Limit::perMinutes(
                    Config::get('nucleus.api.throttle.expires'),
                    Config::get('nucleus.api.throttle.attempts')
                )->by($request->user()?->id ?: $request->ip());
            });

            $rateLimitMiddleware = 'throttle:api';
        }

        return $rateLimitMiddleware;
    }

    /**
     * @return  mixed
     */
    private function getApiUrl()
    {
        return Config::get('nucleus.api.url');
    }

    /**
     * @param $file
     *
     * @return  string
     */
    private function getApiVersionPrefix($file): string
    {
        return Config::get('nucleus.api.prefix') . (Config::get(
            'nucleus.api.enable_version_prefix'
        ) ? $this->getRouteFileVersionFromFileName($file) : '');
    }

    /**
     * @param $file
     *
     * @return  string|bool
     */
    private function getRouteFileVersionFromFileName($file): string|bool
    {
        $fileNameWithoutExtension = $this->getRouteFileNameWithoutExtension($file);

        $fileNameWithoutExtensionExploded = explode('.', $fileNameWithoutExtension);

        end($fileNameWithoutExtensionExploded);

        $apiVersion = prev($fileNameWithoutExtensionExploded);

        // Skip versioning the API's root route
        if ('ApisRoot' === $apiVersion) {
            $apiVersion = '';
        }

        return $apiVersion;
    }

    /**
     * @param SplFileInfo $file
     *
     * @return  mixed
     */
    private function getRouteFileNameWithoutExtension(SplFileInfo $file): mixed
    {
        return pathinfo($file->getFileName())['filename'];
    }

    /**
     * Register the Containers WEB routes files
     *
     * @param $containerPath
     */
    private function loadWebContainerRoutes($containerPath): void
    {
        // build the container web routes path
        $webRoutesPath = $containerPath . DIRECTORY_SEPARATOR . 'UI' . DIRECTORY_SEPARATOR . 'WEB' . DIRECTORY_SEPARATOR . 'Routes';

        if (File::isDirectory($webRoutesPath)) {
            $files = File::allFiles($webRoutesPath);
            $files = Arr::sort($files, fn($file) => $file->getFilename());

            foreach ($files as $file) {
                $this->loadWebRoute($file);
            }
        }
    }

    /**
     * @param $file
     */
    private function loadWebRoute($file): void
    {
        Route::group(
            ['middleware' => ['web']],
            fn($router) => require $file->getPathname()
        );
    }
}
