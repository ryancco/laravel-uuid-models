<?php

namespace Ryancco\HasUuidRouteKey;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @property UuidInterface $uuid
 */
trait HasUuidRouteKey
{
    /**
     * @return UuidInterface
     * @throws InvalidUuidStringException
     */
    public function getUuidAttribute(): ?UuidInterface
    {
        $attribute = $this->attributes[$this->getRouteKeyName()] ?? null;

        return !$attribute ? null : Uuid::fromString($attribute);
    }

    /**
     * @param string|UuidInterface $uuid
     *
     * @throws InvalidUuidStringException
     */
    public function setUuidAttribute($uuid): void
    {
        if (!$uuid instanceof UuidInterface) {
            $uuid = Uuid::fromString($uuid);
        }

        $this->attributes[$this->getRouteKeyName()] = $uuid->toString();
    }

    public static function bootHasUuidRouteKey(): void
    {
        static::creating(static function ($instance) {
            $instance->generateUuidRouteKey();
        });
    }

    public function generateUuidRouteKey(): void
    {
        if (empty($this->attributes[$this->getRouteKeyName()])) {
            $this->setAttribute($this->getRouteKeyName(), Str::orderedUuid());
        }
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
