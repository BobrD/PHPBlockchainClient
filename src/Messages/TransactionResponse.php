<?php

namespace Bookie\Blockchain\Messages;

class TransactionResponse
{
    /**
     * @var string
     */
    private $transactionHash;

    /**
     * @param string $transactionHash
     */
    public function __construct(string $transactionHash)
    {
        $this->transactionHash = $transactionHash;
    }

    /**
     * @return string
     */
    public function getTransactionHash(): string
    {
        return $this->transactionHash;
    }
}