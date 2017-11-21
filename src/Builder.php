<?php

namespace Bookie\Blockchain;


use Bookie\Blockchain\Exception\BuilderException;
use Bookie\Blockchain\Normalizer\BetNormalizer;
use Symfony\Component\Serializer\Serializer;

class Builder
{
    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    public function client(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function host(string $host)
    {
        $this->host = $host;
    }

    public function port(int $port)
    {
        $this->port = $port;
    }

    public function build(): BookieBetBlockChainInterface
    {
        return new BookieBetBlockChain($this->getClient(), $this->getSerializer());
    }

    private function getSerializer(): Serializer
    {
        return new Serializer([
            new BetNormalizer()
        ]);
    }

    private function getClient(): HttpClientInterface
    {
        if (null !== $this->client) {
            return $this->client;
        }

        $this->assertRequire('host', 'port');

        return new HttpClient($this->host, $this->port);
    }


    private function assertRequire(...$fields)
    {
        $missed = array_filter($fields, function ($field) {
            return null === $this->{$field};
        });

        if (0 !== count($missed)) {
            throw new BuilderException($missed);
        }
    }
}