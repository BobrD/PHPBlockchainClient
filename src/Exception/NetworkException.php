<?php

namespace Bookie\Blockchain\Exception;

class NetworkException extends AbstractBlockchainException
{
    public function __construct(string $endpoint, string $error)
    {
        parent::__construct(sprintf('Network request to endpoint: "%s" failed with error: "%s".', $endpoint, $error));
    }
}
