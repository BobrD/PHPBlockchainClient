<?php

namespace Bookie\Blockchain\Normalizer;

use Bookie\Blockchain\Transaction;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class TransactionNormalizer implements DenormalizerInterface
{
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        return new Transaction(
            $data['transactionHash'],
            $data['contractUuid'],
            $data['method'],
            $data['args'],
            $data['state'],
            $data['result']
        );
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return Transaction::class === $type;
    }
}