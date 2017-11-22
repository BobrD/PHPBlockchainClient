<?php

namespace Bookie\Blockchain;

use Bookie\Blockchain\Exception\NetworkException;

interface HttpClientInterface
{
    /**
     * Send post request to http client and return array.
     *
     * Ex: ['error' => 'some error']; ['error' => null, 'payload' => [...]];
     *
     * If network error occurred throw @NetworkException.
     *
     * @param string $endpoint
     *
     * @param array $data
     *
     * @throws NetworkException
     *
     * @return array
     */
    public function sendPost(string $endpoint, array $data): array;
}