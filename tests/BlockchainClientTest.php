<?php

namespace Tests\Bookie\Blockchain;

use Bookie\Blockchain\Bet;
use Bookie\Blockchain\BetResult;
use Bookie\Blockchain\BetResultType;
use Bookie\Blockchain\BetType;
use Bookie\Blockchain\BlockchainClient;
use Bookie\Blockchain\Builder;
use Bookie\Blockchain\ContractMethod;
use Bookie\Blockchain\Messages\CreateFastBetResponse;
use Bookie\Blockchain\TransactionState;
use PHPUnit\Framework\TestCase;

/**
 * It's p2p test and require that local http server is running.
 */
class BlockchainClientTest extends TestCase
{
    /**
     * @var BlockchainClient
     */
    private $client;

    public function setUp()
    {
        $this->client = (new Builder())->host('http://http_server')->port(8088)->build();
    }

    public function testCreateBetReturnTransaction()
    {
        $bet = $this->creteBetInst();

        $transactionHash = $this->client->createBet($bet)->getTransactionHash();
        $uuid = $this->client->createBet($bet)->getUuid();

        $this->assertRegExp('#0x.*?#', $transactionHash);
        $this->assertNotEmpty($uuid);
    }

    public function testGetTransactionState()
    {
        $transactionHash = $this->client->createBet($this->creteBetInst())->getTransactionHash();

        $states = [];
        while (true) {
            $transaction = $this->client->getTransaction($transactionHash);

            $states = [$transaction->getState()->getState()];

            if ($transaction->getState()->eq(TransactionState::DONE)) {
                break;
            }
        }

        $this->assertNotEmpty($states);
    }

    public function testGetContractAddress()
    {
        $uuid = $this->createAndWait();

        $result = $this->client->getContractAddress($uuid);

        $this->assertNotEmpty($result);
    }

    public function testGetBet()
    {
        $uuid = $this->createAndWait();

        $betFromChain = $this->client->getBet($uuid);

        $this->assertInstanceOf(Bet::class, $betFromChain);
    }

    public function testAddResult()
    {
        $uuid = $this->createAndWait();

        $addResultResponse = $this->client->addResult($uuid, new BetResult(time(), BetResultType::WIN, 150));

        $this->waiteDone($addResultResponse->getTransactionHash());

        $this->assertInstanceOf(BetResult::class, $this->client->getResultAt($uuid, 0));
        $this->assertNull($this->client->getResultAt($uuid, 1));
    }

    public function testCommit()
    {
        $uuid = $this->createAndWait();

        $commitResponse = $this->client->commit($uuid);

        $this->waiteDone($commitResponse->getTransactionHash());

        $this->assertTrue($this->client->isCommitted($uuid));
    }

    public function testGetBetResults()
    {
        $uuid = $this->createAndWait();

        $addResultResponse = $this->client->addResult($uuid, new BetResult(time(), BetResultType::WIN, 150));

        $this->waiteDone($addResultResponse->getTransactionHash());

        $results = $this->client->getBetResults($uuid);

        $this->assertCount(1, $results);

        $this->assertInstanceOf(BetResult::class, $results[0]);
    }

    public function testIsCommitted()
    {
        $uuid = $this->createAndWait();

        $this->assertFalse($this->client->isCommitted($uuid));

        $this->waiteDone($this->client->commit($uuid)->getTransactionHash());

        $this->assertTrue($this->client->isCommitted($uuid));

    }

    public function testGetCountResults()
    {
        $uuid = $this->createAndWait();

        $this->assertEquals(0, $this->client->getCountResults($uuid));

        $response = $this->client->addResult($uuid, new BetResult(time(), BetResultType::WIN, 150));

        $this->waiteDone($response->getTransactionHash());

        $this->assertEquals(1, $this->client->getCountResults($uuid));
    }

    public function testCreateFastBet()
    {
        $result = $this->client->createFastBet($this->creteBetInst());

        $this->assertInstanceOf(CreateFastBetResponse::class, $result);
        $this->assertNotEmpty($result->getUuid());
    }

    public function testGetCreateBetTransactionHash()
    {
        $result = $this->client->createFastBet($this->creteBetInst());

        $hash = null;

        foreach (range(0, 5) as $_) {
            sleep(1);

            $hash = $this->client->getCreateBetTransactionHash($result->getUuid());

            if (null !== $hash) {
                break;
            }
        }

        $this->assertNotNull($hash);
    }

    /**
     * @param string $transactionHash
     */
    private function waiteDone(string $transactionHash)
    {
        while (true) {
            $transaction = $this->client->getTransaction($transactionHash);

            if ($transaction->getState()->eq(TransactionState::DONE)) {
                break;
            }

            sleep(1);
        }
    }

    private function creteBetInst()
    {
        return  new Bet(
            'id',
            'playerName',
            100,
            'EUR',
            1.3,
            BetType::create(BetType::COMBINED),
            'wager',
            'eventTitle',
            true,
            time()
        );
    }

    private function createAndWait(): string
    {
        $createResponse = $this->client->createBet($this->creteBetInst());

        $this->waiteDone($createResponse->getTransactionHash());

        return $createResponse->getUuid();
    }
}
