<?php

namespace Bookie\Blockchain;

use Bookie\Blockchain\Exception\NetworkException;

interface BookieBetBlockChainInterface
{
    /**
     * Create contact in blockchain and return transaction hash.
     *
     * @param Bet $bet
     *
     * @return string
     *
     * @throws NetworkException
     */
    public function createBet(Bet $bet): string;

    /**
     * Request contract address by transaction hash. If exist return it, else null.
     *
     * @param string $transactionHash
     *
     * @return string|null
     *
     * @throws NetworkException
     */
    public function getContractAddress(string $transactionHash);

    /**
     * @param string $transactionHash
     *
     * @return ContractState
     *
     * @throws NetworkException
     */
    public function getContractState(string $transactionHash): ContractState;

    /**
     * @param string $transactionHash
     *
     * @return Bet
     *
     * @throws NetworkException
     */
    public function getBet(string $transactionHash);

    /**
     * todo return type
     *
     * @param string $transactionHash
     *
     * @param BetResult $result
     *
     * @throws NetworkException
     */
    public function settleBet(string $transactionHash, BetResult $result);

    /**
     * @param string $transactionHash
     *
     * @return mixed
     */
    public function finish(string $transactionHash);

    /**
     * @param string $transactionHash
     *
     * @return BetResult[]
     *
     * @throws NetworkException
     */
    public function getBetResults(string $transactionHash): array;

    /**
     * @param string $transactionHash
     *
     * @return bool
     */
    public function isFinished(string $transactionHash): bool;
}