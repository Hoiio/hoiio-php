<?php

/**
 * Represents rate information for a SMS.
 *
 */
class SMSRate {
    private $currency = '';
    private $rate = 0;
    private $splitCount = 0;
    private $totalCost = 0;
    private $isUnicode = false;

    /**
     * Currency code that this SMS will be billed.
     *
     * @return string   Currency Code
     */
    public function getCurrency() {
        return $this->currency;
    }

   /**
     * Cost per SMS (in currency indicated in currency code).
     *
     * @return float    Cost per SMS
     */
    public function getRate() {
        return $this->rate;
    }

    /**
     * How many SMS is required to send this message.
     *
     * @return int  Number of SMS that this message will use
     */
    public function getSplitCount() {
        return $this->splitCount;
    }

    /**
     * Total cost required for sending this message.
     *
     * @return float    Total cost for sending this message
     */
    public function getTotalCost() {
        return $this->totalCost;
    }

    /**
     * Whether this message contains any unicode characters.
     *
     * @return bool Whether this message contains any unicode characters
     */
    public function isUnicode() {
        return $this->isUnicode;
    }

    public function __construct($currency, $rate, $splitCount, $totalCost, $isUnicode) {
        $this->currency = $currency;
        $this->rate = $rate;
        $this->splitCount = $splitCount;
        $this->totalCost = $totalCost;
        $this->isUnicode = $isUnicode;
    }
}
