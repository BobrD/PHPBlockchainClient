<?php

namespace Bookie\Blockchain;

final class ContractMethod
{
    use FlyweightTrait {
        FlyweightTrait::create as FlyweightCreate;
        FlyweightTrait::__construct as private flyweight__construct;
    }

    // [!] don't rename const value

    const COMMIT = 'commit';

    const ADD_RESULT = 'addResult';

    const CREATE = 'create';


    /**
     * @param $type
     */
    private function __construct(string $type)
    {
        $this->flyweight__construct($type, 'Invalid contract method.');
    }

    /**
     * @param string|ContractMethod $type
     *
     * @return ContractMethod
     */
    public static function create($type): ContractMethod
    {
        return static::FlyweightCreate($type);
    }

    public function getMethod(): string
    {
        return $this->getValue();
    }
}