<?php

namespace Bookie\Blockchain\Normalizer;

use Bookie\Blockchain\Bet;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class BetNormalizer implements NormalizerInterface, DenormalizerInterface
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
            'betType' => $object->getBetType()->getType(),
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

    public function denormalize($data, $class, $format = null, array $context = array())
    {
        if (!is_array($data)) {
            return null;
        }

        return new Bet(
            $data['id'],
            $data['playerName'],
            $data['amount'],
            $data['currency'],
            $data['odds'],
            $data['betType'],
            $data['wager'],
            $data['eventTitle'],
            $data['live'],
            $data['betTime']
        );
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === Bet::class;
    }
}