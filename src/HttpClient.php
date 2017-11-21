<?php

namespace Bookie\Blockchain;

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
     * @param string $endPoint
     *
     * @param array $data
     *
     * @return mixed
     */
    public function sendPost(string $endPoint, array $data)
    {
        $url = $this->host.$endPoint.':'.$this->port;

        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        
        $result = curl_exec($ch);
        
        curl_close($ch);
        
        return $result;
    }
}