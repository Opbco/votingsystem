<?php

namespace App\Form;

final class CandidatureStatus
{
    public const ONGOING = 'registered';
    public const TRANSFERED = 'valid';
    public const RETIRED = 'rejected';
    public const ADMITTED = 'admitted';

    private static $instances = [];

    private string $value;

    private function __construct(string $value)
    {
        if (!array_key_exists($value, self::choices())) {
            throw new \DomainException(sprintf('The value "%s" is not a valid moderation status.', $value));
        }

        $this->value = $value;
    }

    public static function byValue(string $value): CandidatureStatus
    {
        // limitation of count object instances
        if (!isset(self::$instances[$value])) {
            self::$instances[$value] = new static($value);
        }

        return self::$instances[$value];
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function choices(): array
    {
        return [
            self::ONGOING => 'registered',
            self::TRANSFERED => 'valid',
            self::RETIRED => 'rejected',
            self::ADMITTED => 'admitted',
        ];
    }

    public function __toString(): string
    {
        return self::choices()[$this->value];
    }
}
