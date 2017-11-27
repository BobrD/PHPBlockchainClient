<?php

namespace Tests\Bookie\Blockchain;

use Bookie\Blockchain\Bet;
use Bookie\Blockchain\BetType;
use Bookie\Blockchain\BlockchainClient;
use Bookie\Blockchain\Builder;
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

        $response = $this->client->createBet($bet);

        $this->waiteDone($response->getTransactionHash());

        $uuid = $response->getUuid();

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

    }

    public function testIsCommitted()
    {

    }

    public function testGetCountResults()
    {

    }

    public function testGetResultAt()
    {

    }

    private function waiteDone($transactionHash)
    {
        while (true) {
            $transaction = $this->client->getTransaction($transactionHash);

            if ($transaction->getState()->eq(TransactionState::DONE)) {
                break;
            }
        }
    }
}
