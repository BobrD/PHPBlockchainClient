<?php

namespace Bookie\Blockchain;

use Symfony\Component\Serializer\Serializer;

class BookieBetBlockChain implements BookieBetBlockChainInterface
{
    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(HttpClientInterface $client, Serializer $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }

    public function createBet(Bet $bet): string
    {
        $responce = $this->client->sendPost('/create_bet', [
            'bet' => $this->serializer->normalize($bet)
        ]);
    }

    public function getContractAddress(string $transactionHash)
    {
        // TODO: Implement getContractAddress() method.
    }

    public function getContractState(string $transactionHash): ContractState
    {
        // TODO: Implement getContractState() method.
    }

    public function getBet(string $transactionHash)
    {
        // TODO: Implement getBet() method.
    }

    public function settleBet(string $transactionHash, BetResult $result)
    {
        // TODO: Implement settleBet() method.
    }

    public function finish(string $transactionHash)
    {
        // TODO: Implement finish() method.
    }

    public function getBetResults(string $transactionHash): array
    {
        // TODO: Implement getBetResults() method.
    }

    public function isFinished(string $transactionHash): bool
    {
        // TODO: Implement isFinished() method.
    }

}