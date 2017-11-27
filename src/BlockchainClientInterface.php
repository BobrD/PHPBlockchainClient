<?php

namespace Bookie\Blockchain;

use Bookie\Blockchain\Exception\NetworkException;
use Bookie\Blockchain\Messages\CreateBetResponse;
use Bookie\Blockchain\Messages\TransactionResponse;

interface BlockchainClientInterface
{
    /**
     * Create contact in blockchain and return transaction hash.
     *
     * @param Bet $bet
     *
     * @return CreateBetResponse
     *
     * @throws NetworkException
     */
    public function createBet(Bet $bet): CreateBetResponse;

    /**
     * Request contract address by transaction hash. If exist return it, else null.
     *
     * @param string $uuid
     *
     * @return string|null
     *
     * @throws NetworkException
     */
    public function getContractAddress(string $uuid);

    /**
     * @param string $transactionHash
     *
     * @return Transaction
     *
     * @throws NetworkException
     */
    public function getTransaction(string $transactionHash): Transaction;

    /**
     * @param string $uuid
     *
     * @return Bet
     *
     * @throws NetworkException
     */
    public function getBet(string $uuid): Bet;

    /**
     * todo return type
     *
     * @param string $uuid
     *
     * @param BetResult $result
     *
     * @throws NetworkException
     *
     * @return TransactionResponse
     */
    public function addResult(string $uuid, BetResult $result): TransactionResponse;

    /**
     * @param string $uuid
     *
     * @return TransactionResponse
     */
    public function commit(string $uuid): TransactionResponse;

    /**
     * @param string $uuid
     *
     * @return BetResult[]
     *
     * @throws NetworkException
     */
    public function getBetResults(string $uuid): array;

    /**
     * @param string $uuid
     *
     * @return int
     *
     * @throws NetworkException
     */
    public function getCountResults(string $uuid): int;

    /**
     * @param string $uuid
     * @param int $index
     *
     * @return BetResult|null
     *
     */
    public function getResultAt(string $uuid, int $index);

    /**
     * @param string $uuid
     *
     * @return bool
     */
    public function isCommitted(string $uuid): bool;
}