<?php

namespace Bookie\Blockchain;


use Bookie\Blockchain\Exception\BuilderException;
use Bookie\Blockchain\Normalizer\BetNormalizer;
use Bookie\Blockchain\Normalizer\BetResultNormalizer;
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

    /**
     * @param HttpClientInterface $client
     */
    public function client(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $host
     */
    public function host(string $host)
    {
        $this->host = $host;
    }

    /**
     * @param int $port
     */
    public function port(int $port)
    {
        $this->port = $port;
    }

    /**
     * @return BlockchainClientInterface
     */
    public function build(): BlockchainClientInterface
    {
        return new BlockchainClient($this->getClient(), $this->getSerializer());
    }

    /**
     * @return Serializer
     */
    private function getSerializer(): Serializer
    {
        return new Serializer([
            new BetNormalizer(),
            new BetResultNormalizer()
        ]);
    }

    /**
     * @return HttpClientInterface
     */
    private function getClient(): HttpClientInterface
    {
        if (null !== $this->client) {
            return $this->client;
        }

        $this->assertRequire('host', 'port');

        return new HttpClient($this->host, $this->port);
    }

    /**
     * @param array ...$fields
     */
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
