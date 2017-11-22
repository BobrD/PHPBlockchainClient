<?php

namespace Bookie\Blockchain;

final class BetResultType
{
    use FlyweightTrait {
        FlyweightTrait::create as FlyweightCreate;
    }

    const WIN = 'WIN';

    const ROLLBACK = 'ROLLBACK';

    const CANCEL = 'CANCEL';

    const CASHOUT = 'CASHOUT';

    const LOST = 'LOST';

    /**
     * @var string
     */
    private $type;

    /**
     * @param string $type
     */
    private function __construct(string $type)
    {
        $this->assertType($type,'Invalid bet result type');

        $this->type = $type;
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
        return $this->type;
    }
}
