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

    /**
     * {@inheritdoc}
     */
    public function createBet(Bet $bet): string
    {
        $endPoint = '/create_bet';

        $response = $this->client->sendPost($endPoint, [
            'bet' => $this->serializer->normalize($bet)
        ]);

        $error = $response['error'];

        $transactionHash = $response['payload']['transactionHash'];

        $this->assertError($endPoint, $error);

        return $transactionHash;
    }

    /**
     * {@inheritdoc}
     */
    public function getContractAddress(string $transactionHash)
    {
        $endPoint = '/get_contract_address';

        $response = $this->client->sendPost($endPoint, [
            'transactionHash' => $transactionHash
        ]);

        $error = $response['error'];

        $address = $response['payload']['address'];

        $this->assertError($endPoint, $error);

        return $address;
    }

    /**
     * {@inheritdoc}
     */
    public function getContractState(string $transactionHash): ContractState
    {
        // TODO: Implement getContractState() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getBet(string $transactionHash): Bet
    {
        $endPoint = '/get_bet';

        $response = $this->client->sendPost($endPoint, [
            'transactionHash' => $transactionHash
        ]);

        $error = $response['error'];

        $bet = $response['payload']['bet'];

        $this->assertError($endPoint, $error);

        return $this->serializer->denormalize($bet, Bet::class);
    }

    /**
     * {@inheritdoc}
     */
    public function addResult(string $transactionHash, BetResult $result)
    {
        $endPoint = '/add_result';

        $response = $this->client->sendPost($endPoint, [
            'transactionHash' => $transactionHash,
            'result' => $this->serializer->normalize($result)
        ]);

        $error = $response['error'];


        $this->assertError($endPoint, $error);
    }

    /**
     * {@inheritdoc}
     */
    public function commit(string $transactionHash)
    {
        $endPoint = '/commit';

        $response = $this->client->sendPost($endPoint, [
            'transactionHash' => $transactionHash,
        ]);

        $error = $response['error'];

        $this->assertError($endPoint, $error);
    }

    /**
     * {@inheritdoc}
     */
    public function getBetResults(string $transactionHash): array
    {
        $results = $this->getCountResults($transactionHash);

        if (0 === $results) {
            return [];
        }

        return array_map(function ($index) use ($transactionHash) {
            return $this->getResultAt($transactionHash, $index);
        }, range(0,  $results - 1));
    }

    /**
     * {@inheritdoc}
     */
    public function isCommitted(string $transactionHash): bool
    {
        $endPoint = '/is_committed';

        $response = $this->client->sendPost($endPoint, [
            'transactionHash' => $transactionHash
        ]);

        $error = $response['error'];

        $committed = $response['payload']['committed'];

        $this->assertError($endPoint, $error);

        return $committed;
    }

    /**
     * {@inheritdoc}
     */
    public function getCountResults(string $transactionHash): int
    {
        $endPoint = '/get_count_results';

        $response = $this->client->sendPost($endPoint, [
            'transactionHash' => $transactionHash
        ]);

        $error = $response['error'];

        $count = $response['payload']['count'];

        $this->assertError($endPoint, $error);

        return $count;
    }

    /**
     * {@inheritdoc}
     */
    public function getResultAt(string $transactionHash, int $index)
    {
        $endPoint = '/get_result_at';

        $response = $this->client->sendPost($endPoint, [
            'transactionHash' => $transactionHash,
            'index' => $index
        ]);

        $error = $response['error'];

        $result = $response['payload']['result'];

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