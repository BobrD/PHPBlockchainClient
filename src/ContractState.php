<?php

namespace Bookie\Blockchain;

/**
 * State of contract in blockchain.
 */
final class ContractState
{
    use FlyweightTrait {
        FlyweightTrait::create as FlyweightCreate;
    }

    const PENDING = 'PENDING';

    const CREATED = 'CREATED';

    const ERROR = 'ERROR';

    /**
     * @var string
     */
    private $state;

    /**
     * @param string $state
     */
    private function __construct(string $state)
    {
        $this->assertType($state, 'Invalid transaction state.');

        $this->state = $state;
    }

    /**
     * @param string|ContractState $type
     *
     * @return ContractState
     */
    public static function create($type): ContractState
    {
        return self::FlyweightCreate($type);
    }

    /***
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }
}