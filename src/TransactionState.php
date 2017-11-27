<?php

namespace Bookie\Blockchain;

/**
 * State of contract in blockchain.
 */
final class TransactionState
{
    use FlyweightTrait {
        FlyweightTrait::create as FlyweightCreate;
        FlyweightTrait::__construct as private flyweight__construct;
    }

    // [!] don't rename const value

    const PENDING = 'pending';

    const DONE = 'done';

    const ERROR = 'error';

    /**
     * @param string $state
     */
    private function __construct(string $state)
    {
        $this->flyweight__construct($state, 'Invalid transaction state.');
    }

    /**
     * @param string|TransactionState $type
     *
     * @return TransactionState
     */
    public static function create($type): TransactionState
    {
        return self::FlyweightCreate($type);
    }

    /***
     * @return string
     */
    public function getState(): string
    {
        return $this->getValue();
    }
}
