<?php

namespace Bookie\Blockchain;

use Bookie\Blockchain\Exception\RequestFailedException;
use Symfony\Component\Serializer\Serializer;

class BlockchainClient implements BlockchainClientInterface
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
        $endPoint = '/create_bet';

        ['error' => $error, 'payload' => ['transactionHash' => $transactionHash]] = $this->client->sendPost($endPoint, [
            'bet' => $this->serializer->normalize($bet)
        ]);

        $this->assertError($endPoint, $error);

        return $transactionHash;
    }

    public function getContractAddress(string $transactionHash)
    {
        // TODO: Implement getContractState() method.
    }

    public function getContractState(string $transactionHash): ContractState
    {
        // TODO: Implement getContractState() method.
    }

    public function getBet(string $transactionHash): Bet
    {
        $endPoint = '/get_bet';

        ['error' => $error, 'payload' => ['bet' => $bet]] = $this->client->sendPost($endPoint, [
            'transactionHash' => $transactionHash
        ]);

        $this->assertError($endPoint, $error);

        return $this->serializer->denormalize($bet, Bet::class);
    }

    public function addResult(string $transactionHash, BetResult $result)
    {
        $endPoint = '/add_result';

        ['error' => $error] = $this->client->sendPost($endPoint, [
            'transactionHash' => $transactionHash,
            'result' => $this->serializer->normalize($result)
        ]);

        $this->assertError($endPoint, $error);
    }

    public function commit(string $transactionHash)
    {
        $endPoint = '/commit';

        ['error' => $error] = $this->client->sendPost($endPoint, [
            'transactionHash' => $transactionHash,
        ]);

        $this->assertError($endPoint, $error);
    }

    public function getBetResults(string $transactionHash): array
    {
        // TODO: Implement getBetResults() method.
    }

    public function isCommitted(string $transactionHash): bool
    {
        $endPoint = '/is_committed';

        ['error' => $error, 'payload' => ['committed' => $committed]] = $this->client->sendPost($endPoint, [
            'transactionHash' => $transactionHash
        ]);

        $this->assertError($endPoint, $error);

        return $committed;
    }

    public function getCountResults(): int
    {
        // TODO: Implement getCountResults() method.
    }

    public function getResultAt(string $transactionHash, int $index)
    {
        $endPoint = '/get_result_at';

        ['error' => $error, 'payload' => ['result' => $result]] = $this->client->sendPost($endPoint, [
            'transactionHash' => $transactionHash,
            'index' => $index
        ]);

        $this->assertError($endPoint, $error);

        return $this->serializer->denormalize($result, BetResult::class);
    }

    /**
     * @param string $endPoint
     * @param string|null $error
     *
     * @throws RequestFailedException
     */
    private function assertError(string $endPoint, $error)
    {
        if (null !== $error) {
            throw new RequestFailedException($endPoint, $error);
        }
    }
}