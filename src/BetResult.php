<?php

namespace Bookie\Blockchain;

final class BetResult
{
    /**
     * @var int
     */
    private $time;

    /**
     * @var BetResultType
     */
    private $type;

    /**
     * @var float
     */
    private $amount;

    /**
     * @param int $time
     * @param BetResultType|string $type
     * @param float $amount
     */
    public function __construct(int $time, $type, float $amount)
    {
        $this->time = $time;
        $this->type = BetResultType::create($type);
        $this->amount = $amount;
    }

    public function getTime(): int
    {
        return $this->time;
    }

    public function getType(): BetResultType
    {
        return $this->type;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}