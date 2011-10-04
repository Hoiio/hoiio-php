<?php

/**
 * Represents rate information for a Call.
 *
 */
class CallRate {
    private $currency = '';
    private $rate = 0;
    private $talkTime = 0;

    /**
     * Currency code that this Call will be billed.
     *
     * @return string   Currency Code
     */
    public function getCurrency() {
        return $this->currency;
    }

    /**
     * Cost per minute (in currency indicated in currency code).
     *
     * @return float    Cost per minute
     */
    public function getRate() {
        return $this->rate;
    }

    /**
     * Maximum talk time allowed for this call.
     *
     * @return float    Talk time in minutes
     */
    public function getTalkTime() {
        return $this->talkTime;
    }

    public function __construct($currency, $rate, $talkTime) {
        $this->currency = $currency;
        $this->rate = $rate;
        $this->talkTime = $talkTime;
    }
}

