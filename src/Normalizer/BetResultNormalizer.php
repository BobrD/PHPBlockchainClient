<?php

namespace Bookie\Blockchain\Normalizer;

use Bookie\Blockchain\BetResult;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class BetResultNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /**
     * @param BetResult $object
     * @param null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, $format = null, array $context = array())
    {
        return [
            'time' => $object->getTime(),
            'type' => $object->getType()->type(),
            'amount' => $object->getAmount()
        ];
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof BetResult;
    }

    public function denormalize($data, $class, $format = null, array $context = array())
    {
        return new BetResult(
            $data['time'],
            $data['type'],
            $data['amount']
        );
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === BetResult::class;
    }
}