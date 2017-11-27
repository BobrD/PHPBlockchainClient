<?php

namespace Bookie\Blockchain;

final class Transaction
{
    /**
     * @var string
     */
    private $transactionHash;

    /**
     * @var string
     */
    private $contractUuid;

    /**
     * @var ContractMethod
     */
    private $method;

    /**
     * @var array
     */
    private $args;

    /**
     * @var TransactionState
     */
    private $state;

    /**
     * @var string|null
     */
    private $result;

    /**
     * @param string $transactionHash
     * @param string $contractUuid
     * @param ContractMethod|string $method
     * @param array $args
     * @param TransactionState|string $state
     * @param null|string $result
     */
    public function __construct(string $transactionHash, string $contractUuid, $method, array $args, $state, $result)
    {
        $this->transactionHash = $transactionHash;
        $this->contractUuid = $contractUuid;
        $this->method = ContractMethod::create($method);
        $this->args = $args;
        $this->state = TransactionState::create($state);
        $this->result = $result;
    }


    /**
     * @return string
     */
    public function getTransactionHash(): string
    {
        return $this->transactionHash;
    }

    /**
     * @return string
     */
    public function getContractUuid(): string
    {
        return $this->contractUuid;
    }

    /**
     * @return ContractMethod
     */
    public function getMethod(): ContractMethod
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * @return TransactionState
     */
    public function getState(): TransactionState
    {
        return $this->state;
    }

    /**
     * @return null|string
     */
    public function getResult(): string
    {
        return $this->result;
    }
}