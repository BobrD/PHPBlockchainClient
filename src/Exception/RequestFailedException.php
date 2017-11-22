<?php

namespace Bookie\Blockchain\Exception;

class RequestFailedException extends AbstractBlockchainException
{
    /**
     * @param string $endpoint
     * @param string $error
     */
    public function __construct(string $endpoint, string $error)
    {
        parent::__construct(sprintf('Request to endpoint: "%s" failed with error message: "%s".', $endpoint, $error));
    }
}
