<?php

namespace Bookie\Blockchain;

use Bookie\Blockchain\Exception\RequestFailedException;
use Bookie\Blockchain\Messages\CreateBetResponse;
use Bookie\Blockchain\Messages\TransactionResponse;
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
    public function createBet(Bet $bet): CreateBetResponse
    {
        $payload = $this->send('/create_bet', ['bet' => $this->serializer->normalize($bet)]);

        return new CreateBetResponse($payload['transactionHash'], $payload['uuid']);
    }

    /**
     * {@inheritdoc}
     */
    public function getContractAddress(string $uuid)
    {
        return $this->send('/get_contract_address', ['uuid' => $uuid])['address'];
    }

    /**
     * {@inheritdoc}
     */
    public function getTransaction(string $transactionHash): Transaction
    {
        $transaction = $this->send('/get_transaction', ['transactionHash' => $transactionHash])['transaction'];

        return $this->serializer->denormalize($transaction, Transaction::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getBet(string $uuid): Bet
    {
        $bet = $this->send('/get_bet', ['uuid' => $uuid])['bet'];

        return $this->serializer->deserialize($bet, Bet::class, 'json');
    }

    /**
     * {@inheritdoc}
     */
    public function addResult(string $uuid, BetResult $result): TransactionResponse
    {
        return $this->sendTransaction('/add_result', [
            'uuid' => $uuid,
            'result' => $this->serializer->normalize($result)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function commit(string $uuid): TransactionResponse
    {
        return $this->sendTransaction('/commit', [
            'uuid' => $uuid,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBetResults(string $uuid): array
    {
        $results = $this->getCountResults($uuid);

        if (0 === $results) {
            return [];
        }

        return array_map(function ($index) use ($uuid) {
            return $this->getResultAt($uuid, $index);
        }, range(0,  $results - 1));
    }

    /**
     * {@inheritdoc}
     */
    public function isCommitted(string $uuid): bool
    {
        return $this->send('/is_committed', ['uuid' => $uuid])['committed'];
    }

    /**
     * {@inheritdoc}
     */
    public function getCountResults(string $uuid): int
    {
        return $this->send('/get_count_results', ['uuid' => $uuid])['count'];
    }

    /**
     * {@inheritdoc}
     */
    public function getResultAt(string $uuid, int $index)
    {
        $result = $this->send('/get_result_at', ['uuid' => $uuid, 'index' => $index])['result'];

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

    private function sendTransaction(string $endPoint, array $params): TransactionResponse
    {
        return new TransactionResponse($this->send($endPoint, $params)['transactionHash']);
    }

    /**
     * Return payload.
     *
     * @param string $endPoint
     * @param array $params
     *
     * @return array
     */
    private function send(string $endPoint, array $params): array
    {
        $response = $this->client->sendPost($endPoint, $params);

        $this->assertError($endPoint, $response['error']);

        return $response['payload'];
    }
}