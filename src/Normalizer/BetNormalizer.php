<?php

namespace Bookie\Blockchain\Normalizer;


use Bookie\Blockchain\Bet;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class BetNormalizer implements NormalizerInterface
{
    /**
     * @param Bet $object
     * @param null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, $format = null, array $context = array())
    {
        return [
            'id' => $object->getId(),
            'playerName' => $object->getPlayerName(),
            'amount' => $object->getAmount(),
            'currency' => $object->getCurrency(),
            'odds' => $object->getOdds(),
            'betType' => $object->getBetType(),
            'wager' => $object->getWager(),
            'eventTitle' => $object->getEventTitle(),
            'live' => $object->isLive(),
            'betTime' => $object->getBetTime()
        ];
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Bet;
    }
}