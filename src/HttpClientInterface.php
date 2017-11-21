<?php

namespace Bookie\Blockchain;

interface HttpClientInterface
{
    /**
     * @param string $endPoint
     *
     * @param array $data
     *
     * @return mixed
     */
    public function sendPost(string $endPoint, array $data);
}