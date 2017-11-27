<?php

namespace Tests\Bookie\Blockchain;

use Bookie\Blockchain\Bet;
use Bookie\Blockchain\BetResult;
use Bookie\Blockchain\BetResultType;
use Bookie\Blockchain\BetType;
use Bookie\Blockchain\BlockchainClient;
use Bookie\Blockchain\Builder;
use Bookie\Blockchain\ContractMethod;
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
        $bet = new Bet(
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

        $transactionHash = $this->client->createBet($bet)->getTransactionHash();
        $uuid = $this->client->createBet($bet)->getUuid();

        $this->assertRegExp('#0x.*?#', $transactionHash);
        $this->assertNotEmpty($uuid);
    }

    public function testGetTransactionState()
    {
        $bet = new Bet(
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

        $transactionHash = $this->client->createBet($bet)->getTransactionHash();

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

    }

    public function testGetContractState()
    {

    }

    public function testGetBet()
    {
        $uuid = $this->createAndWait();

        $betFromChain = $this->client->getBet($uuid);

        $this->assertInstanceOf(Bet::class, $betFromChain);
    }

    public function testAddResult()
    {

    }

    public function testCommit()
    {

    }

    public function testGetBetResults()
    {
        $uuid = $this->createAndWait();

        $addResultResponse = $this->client->addResult($uuid, new BetResult(time(), BetResultType::WIN, 150));

        $this->waiteDone($addResultResponse->getTransactionHash(), ContractMethod::ADD_RESULT);

        $results = $this->client->getBetResults($uuid);

        $this->assertCount(1, $results);

        $this->assertInstanceOf(BetResult::class, $results[0]);
    }

    public function testIsCommitted()
    {
        $uuid = $this->createAndWait();

        $this->assertFalse($this->client->isCommitted($uuid));

        $this->waiteDone($this->client->commit($uuid)->getTransactionHash(), ContractMethod::COMMIT);

        $this->assertTrue($this->client->isCommitted($uuid));

    }

    public function testGetCountResults()
    {
        $uuid = $this->createAndWait();

        $this->assertEquals(0, $this->client->getCountResults($uuid));

        $response = $this->client->addResult($uuid, new BetResult(time(), BetResultType::WIN, 150));

        $this->waiteDone($response->getTransactionHash(), ContractMethod::ADD_RESULT);

        $this->assertEquals(1, $this->client->getCountResults($uuid));
    }

    public function testGetResultAt()
    {

    }

    /**
     * @param string $transactionHash
     * @param string|ContractMethod $method
     */
    private function waiteDone($transactionHash, $method)
    {
        while (true) {
            $transaction = $this->client->getTransaction($transactionHash);

            if (
                $transaction->getState()->eq(TransactionState::DONE) &&
                $transaction->getMethod()->eq($method)
            ) {
                break;
            }
        }
    }

    private function createAndWait()
    {
        $bet = new Bet(
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

        $createResponse = $this->client->createBet($bet);

        $this->waiteDone($createResponse->getTransactionHash(), ContractMethod::CREATE);

        return $createResponse->getUuid();
    }
}
