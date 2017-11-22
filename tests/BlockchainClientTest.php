<?php

namespace Tests\Bookie\Blockchain;

use Bookie\Blockchain\Bet;
use Bookie\Blockchain\BetType;
use Bookie\Blockchain\BlockchainClient;
use Bookie\Blockchain\HttpClient;
use Bookie\Blockchain\Normalizer\BetNormalizer;
use Bookie\Blockchain\Normalizer\BetResultNormalizer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Serializer;

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
        $httpClient = new HttpClient(
            'http://http_server',
            8088
        );

        $serializer = new Serializer([
            new BetNormalizer(),
            new BetResultNormalizer()
        ]);

        $this->client = new BlockchainClient(
            $httpClient,
            $serializer
        );
    }


    public function testCreateBet()
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

        $transactionHash = $this->client->createBet($bet);

        $this->assertRegExp('#0x.*?#', $transactionHash);
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

        $transactionHash = $this->client->createBet($bet);

        $betFromChain = $this->client->getBet($transactionHash);

        print_r($betFromChain);
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
}
