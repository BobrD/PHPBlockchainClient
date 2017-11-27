<?php

namespace Bookie\Blockchain;

final class BetResultType
{
    use FlyweightTrait {
        FlyweightTrait::create as FlyweightCreate;
        FlyweightTrait::__construct as private flyweight__construct;
    }

    const WIN = 'WIN';

    const ROLLBACK = 'ROLLBACK';

    const CANCEL = 'CANCEL';

    const CASHOUT = 'CASHOUT';

    const LOST = 'LOST';


    /**
     * @param string $type
     */
    private function __construct(string $type)
    {
        $this->flyweight__construct($type, 'Invalid bet result type');
    }

    public static function create($type): self
    {
        return static::FlyweightCreate($type);
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->getValue();
    }
}
