<?php

namespace Bookie\Blockchain;

use Bookie\Blockchain\Exception\InvalidArgumentException;

trait FlyweightTrait
{
    /**
     * @var string
     */
    private $value;

    private function __construct(string $value, string $errorMessage)
    {
        $this->assertValue($value, $errorMessage);

        $this->value = $value;
    }

    private function assertValue($type, $message)
    {
        $allowedTypes = (new \ReflectionObject($this))->getConstants();

        if (!in_array($type, $allowedTypes, true)) {
            throw new InvalidArgumentException($message.sprintf(
                '"%s" is invalid value allowed: "%s".',
                $type,
                implode(', ', $allowedTypes)
            ));
        }
    }

    /**
     * @param string $type
     *
     * @return mixed
     */
    public static function create($type)
    {
        static $types = [];

        if ($type instanceof self) {
            return $type;
        }

        if (array_key_exists($type, $types)) {
            return $types[$type];
        }

        return $types[$type] = new self($type);
    }

    /**
     * @param self|string $other
     *
     * @return bool
     */
    public function eq($other)
    {
        return $this->value === self::create($other)->value;
    }

    private function getValue(): string
    {
        return $this->value;
    }
}
