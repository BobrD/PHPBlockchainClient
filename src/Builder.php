<?php

namespace Bookie\Blockchain;

use Bookie\Blockchain\Exception\BuilderException;
use Bookie\Blockchain\Normalizer\BetNormalizer;
use Bookie\Blockchain\Normalizer\BetResultNormalizer;
use Bookie\Blockchain\Normalizer\TransactionNormalizer;
use Symfony\Component\Serializer\Encoder\JsonDecode;
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
     *
     * @return $this
     */
    public function client(HttpClientInterface $client): Builder
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @param string $host
     *
     * @return $this
     */
    public function host(string $host): Builder
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @param int $port
     *
     * @return $this
     */
    public function port(int $port): Builder
    {
        $this->port = $port;

        return $this;
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
            new BetResultNormalizer(),
            new TransactionNormalizer()
        ], [
            new JsonDecode(true)
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
