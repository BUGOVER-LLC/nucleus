<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Foundation\AliasLoader;

trait AliasesLoaderTrait
{
    public function loadAliases(): void
    {
        foreach ($this->aliases as $aliasKey => $aliasValue) {
            if (class_exists($aliasValue)) {
                $this->loadAlias($aliasKey, $aliasValue);
            }
        }
    }

    /**
     * @param string $aliasKey
     * @param string $aliasValue
     */
    private function loadAlias(string $aliasKey, string $aliasValue): void
    {
        AliasLoader::getInstance()->alias($aliasKey, $aliasValue);
    }
}
