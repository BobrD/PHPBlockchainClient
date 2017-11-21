<?php

namespace Bookie\Blockchain\Exception;

class BuilderException extends \RuntimeException
{
    public function __construct(array $fields)
    {
        parent::__construct(sprintf('Fields: "%s" is required.', implode(', ', $fields)));
    }
}