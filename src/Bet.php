<?php

namespace Bookie\Blockchain;

final class Bet
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $playerName;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var float
     */
    private $odds;

    /**
     * @var BetType
     */
    private $betType;

    /**
     * @var string
     */
    private $wager;

    /**
     * @var string
     */
    private $eventTitle;

    /**
     * @var bool
     */
    private $live;

    /**
     * @var int
     */
    private $betTime;

    /**
     * @param string $id
     * @param string $playerName
     * @param float $amount
     * @param string $currency
     * @param float $odds
     * @param string|BetType $betType
     * @param string $wager
     * @param string $eventTitle
     * @param bool $live
     * @param int $betTime
     */
    public function __construct(
        string $id,
        string $playerName,
        float $amount,
        string $currency,
        float $odds,
        $betType,
        string $wager,
        string $eventTitle,
        bool $live,
        int $betTime
    ) {

        $this->id = $this->filterVarchar($id, 10);
        $this->playerName = $this->filterVarchar($playerName, 10);
        $this->amount = $amount;
        $this->currency = $this->filterVarchar($currency, 3);
        $this->odds = $odds;
        $this->betType = BetType::create($betType);
        $this->wager = $this->filterVarchar($wager, 25);
        $this->eventTitle = $this->filterVarchar($eventTitle, 100);
        $this->live = $live;
        $this->betTime = $betTime;
    }

    /**
     * Bet id. Max length:
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Player name. Max length:
     *
     * @return string
     */
    public function getPlayerName(): string
    {
        return $this->playerName;
    }

    /**
     * Bet amount.
     *
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Bet currency.
     *
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Bet odds.
     *
     * @return float
     */
    public function getOdds(): float
    {
        return $this->odds;
    }

    /**
     * Bet type
     *
     * @return BetType
     */
    public function getBetType(): BetType
    {
        return $this->betType;
    }

    /**
     * Max length: 25
     *
     * @return string
     */
    public function getWager(): string
    {
        return $this->wager;
    }

    /**
     * Max length: 100
     *
     * @return string
     */
    public function getEventTitle(): string
    {
        return $this->eventTitle;
    }

    public function isLive(): bool
    {
        return $this->live;
    }

    public function getBetTime(): int
    {
        return $this->betTime;
    }

    private function filterVarchar(string $string, int $maxLength): string
    {
        return substr($string, 0, $maxLength);
    }
}
