<?php

namespace Bookie\Blockchain\Exception;

class BuilderException extends AbstractBlockchainException
{
    public function __construct(array $fields)
    {
        parent::__construct(sprintf('Fields: "%s" is required.', implode(', ', $fields)));
    }
}