<?php

declare(strict_types=1);

namespace Nucleus\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Nucleus\Contract\EntityContract;
use Nucleus\Exceptions\CoreInternalErrorException;
use Throwable;

trait CanOwnTrait
{
    /**
     * Checks if the model is the owner of the $ownable model
     * by comparing IDs
     *
     * can be used for OO and OM relations
     *
     * @param EntityContract $ownable
     * @param null $foreignKeyName
     * @param null $localKey
     * @return bool
     * @throws Throwable
     */
    public function owns(EntityContract $ownable, $foreignKeyName = null, $localKey = null): bool
    {
        $foreignKeyName = $foreignKeyName ?: $this->guessForeignKeyName();

        $ownerKey = $ownable->$foreignKeyName;

        throw_if(
            null === $ownerKey,
            (new CoreInternalErrorException())->withErrors(['foreignKeyName' => 'Foreign key name is invalid.'])
        );

        return $ownerKey == ($localKey ?? $this->getKey());
    }

    private function guessForeignKeyName(): string
    {
        $className = Str::snake(class_basename($this));

        return $className . 'Id';
    }

    /**
     * Checks if the model is the owner of the $ownable model
     * can be used for polymorphic relations
     *
     * @param Model $ownable
     * @param null $morphableKeyName
     * @param null $morphableTypeName
     * @param null $localKey
     * @return bool
     */
    public function ownsMorph(
        Model $ownable,
        $morphableKeyName = null,
        $morphableTypeName = null,
        $localKey = null
    ): bool
    {
        [$keyName, $typeName] = $this->guessMorphs($ownable);
        $morphableKeyName = $morphableKeyName ?: $keyName;
        $morphableTypeName = $morphableTypeName ?: $typeName;

        return $ownable->$morphableKeyName == ($localKey ?? $this->getKey(
        )) && $ownable->$morphableTypeName == get_class($this);
    }

    /**
     * @param Model $ownable
     * @return array
     */
    private function guessMorphs(Model $ownable): array
    {
        $className = Str::snake(class_basename($ownable));

        return [$className . 'ableId', $className . 'ableType'];
    }
}
