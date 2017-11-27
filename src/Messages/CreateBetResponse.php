<?php

namespace Bookie\Blockchain\Messages;

class CreateBetResponse extends TransactionResponse
{
    /**
     * @var string
     */
    private $uuid;

    /**
     * @param string $transactionHash
     * @param string $uuid
     */
    public function __construct(string $transactionHash, string $uuid)
    {
        parent::__construct($transactionHash);

        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }
}