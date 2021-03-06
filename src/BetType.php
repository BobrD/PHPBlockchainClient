<?php

namespace Bookie\Blockchain;

final class BetType
{
    use FlyweightTrait {
        FlyweightTrait::create as FlyweightCreate;
        FlyweightTrait::__construct as private flyweight__construct;
    }

    const SINGLE = 'SINGLE';

    const COMBINED = 'COMBINED';

    const SYSTEM = 'SYSTEM';

    const IFBET = 'IFBET';


    /**
     * @param $type
     */
    private function __construct(string $type)
    {
        $this->flyweight__construct($type, 'Invalid bet type.');
    }

    /**
     * @param string|BetType $type
     *
     * @return BetType
     */
    public static function create($type): BetType
    {
        return static::FlyweightCreate($type);
    }

    public function getType(): string
    {
        return $this->getValue();
    }
}
