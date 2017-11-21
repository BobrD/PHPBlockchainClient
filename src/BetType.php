<?php

namespace Bookie\Blockchain;

final class BetType
{
    use FlyweightTrait {
        FlyweightTrait::create as FlyweightCreate;
    }

    const SINGLE = 'SINGLE';

    const COMBINED = 'COMBINED';

    const SYSTEM = 'SYSTEM';

    const IFBET = 'IFBET';

    /**
     * @var string
     */
    private $type;

    /**
     * @param $type
     */
    private function __construct(string $type)
    {
        $this->assertType($type, 'Invalid bet type.');

        $this->type = $type;
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
        return $this->type;
    }
}