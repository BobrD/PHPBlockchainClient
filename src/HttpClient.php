<?php

namespace Bookie\Blockchain;

use Bookie\Blockchain\Exception\NetworkException;

class HttpClient implements HttpClientInterface
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $port;

    public function __construct(string $host, string $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * {@inheritdoc}
     */
    public function sendPost(string $endpoint, array $data): array
    {
        $url = $this->host.':'.$this->port.$endpoint;

        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $result = curl_exec($ch);

        $error  = curl_error($ch);

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if (false === $result) {
            throw new NetworkException($endpoint, $error);
        }

        if (200 !== $code) {
            throw new NetworkException($endpoint, $result);
        }

        $json = json_decode($result, true);

        if (null === $json) {
            throw new NetworkException($endpoint, 'invalid json');
        }

        return $json;
    }
}
