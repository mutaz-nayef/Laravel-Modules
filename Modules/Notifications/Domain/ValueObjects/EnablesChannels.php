<?php

namespace Modules\Notifications\Domain\ValueObjects;

final class EnablesChannels
{
    /**
     * @param  array<string, mixed>  $channels  e.g. ['email', 'push' , 'database']
     */
    public function __construct(private readonly array $channels = [])
    {
    }

    public static function fromJson(string|null $json): self
    {
        if (empty($json)) {
            return self::empty();
        }

        return new self(json_decode($json) ?? []);
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public function isEmpty(): bool
    {
        return empty($this->channels);
    }

    public function has(string $key): bool
    {
        return in_array($key, $this->channels);
    }

    public function toArray(): array
    {
        return $this->channels;
    }
}
