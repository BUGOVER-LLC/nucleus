<?php

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
        $containers_paths = Nuclear::getAllContainerPaths();

        foreach ($containers_paths as $container_path) {
            $this->loadApiContainerRoutes($container_path);
            $this->loadWebContainerRoutes($container_path);
        }
    }

    /**
     * Register the Containers API routes files
     * @param string $container_path
     */
    private function loadApiContainerRoutes(string $container_path): void
    {
        // Build the container api routes path
        $api_routes_path = $container_path . '/UI/API/Routes';
        // Build the namespace from the path
        $controller_namespace = $container_path . '\\UI\API\Controllers';

        if (File::isDirectory($api_routes_path)) {
            $files = File::allFiles($api_routes_path);
            $files = Arr::sort($files, fn($file) => $file->getFilename());

            foreach ($files as $file) {
                $this->loadApiRoute($file, $controller_namespace);
            }
        }
    }

    /**
     * @param $file
     * @param $controllerNamespace
     */
    private function loadApiRoute($file, $controllerNamespace): void
    {
        $routeGroupArray = $this->getRouteGroup($file, $controllerNamespace);

        Route::group($routeGroupArray, function ($router) use ($file) {
            require $file->getPathname();
        });
    }

    /**
     * @param      $endpointFileOrPrefixString
     * @param null $controllerNamespace
     *
     * @return  array
     */
    public function getRouteGroup($endpointFileOrPrefixString, $controllerNamespace = null): array
    {
        return [
            'namespace' => $controllerNamespace,
            'middleware' => $this->getMiddlewares(),
            'domain' => $this->getApiUrl(),
            // If $endpointFileOrPrefixString is a file then get the version name from the file name, else if string use that string as prefix
            'prefix' => is_string(
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

        if (Config::get('nucleus.php.api.throttle.enabled')) {
            RateLimiter::for('api', function (Request $request) {
                return Limit::perMinutes(
                    Config::get('nucleus.php.api.throttle.expires'),
                    Config::get('nucleus.php.api.throttle.attempts')
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
        return Config::get('nucleus.php.api.url');
    }

    /**
     * @param $file
     *
     * @return  string
     */
    private function getApiVersionPrefix($file): string
    {
        return Config::get('nucleus.php.api.prefix') . (Config::get(
                'nucleus.php.api.enable_version_prefix'
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

        $api_version = prev($fileNameWithoutExtensionExploded); // get the array before the last one

        // Skip versioning the API's root route
        if ('ApisRoot' === $api_version) {
            $api_version = '';
        }

        return $api_version;
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
     * @param $container_path
     */
    private function loadWebContainerRoutes($container_path): void
    {
        // build the container web routes path
        $web_routes_path = $container_path . DIRECTORY_SEPARATOR . 'UI' . DIRECTORY_SEPARATOR . 'WEB' . DIRECTORY_SEPARATOR . 'Routes';
        // build the namespace from the path
        $controller_namespace = str_replace(['/', '\\'], '\\', $container_path) . '\\UI\WEB\Controllers';

        if (File::isDirectory($web_routes_path)) {
            $files = File::allFiles($web_routes_path);
            $files = Arr::sort($files, fn($file) => $file->getFilename());

            foreach ($files as $file) {
                $this->loadWebRoute($file, $controller_namespace);
            }
        }
    }

    /**
     * @param $file
     * @param $controller_namespace
     */
    private function loadWebRoute($file, $controller_namespace): void
    {
        Route::group(
            ['namespace' => $controller_namespace, 'middleware' => ['web']], fn($router) => require $file->getPathname()
        );
    }
}
