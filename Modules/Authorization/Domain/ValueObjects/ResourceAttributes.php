<?php

namespace Modules\Authorization\Domain\ValueObjects;

final class ResourceAttributes
{

    public function __construct(private readonly array $attributes = [])
    {
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public static function from(array $attributes): self
    {
        return new self($attributes);
    }

    public function get(string $key, $default = null): mixed
    {
        return $this->attributes[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
}
