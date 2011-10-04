<?php


/**
 * Represents a Call Transaction.
 */
class CallTransaction {
    private $fromStatus = '';
    private $toStatus = '';
    private $txnRef = '';
    private $tag = '';
    private $from = '';
    private $to = '';
    private $date = '';
    private $duration = 0;
    private $currency = '';
    private $rate = 0;
    private $debit = 0;

    /**
     * The dial status of the source number. Look at CallStatus class for possible values.
     *
     * @return string   Dial status
     */
    public function getFromStatus() {
        return $this->fromStatus;
    }

    /**
     * The dial status of the destination number. Look at CallStatus class for possible values.
     *
     * @return string   Dial status
     */
    public function getToStatus() {
        return $this->toStatus;
    }


    /**
     * Transaction Reference of this call.
     *
     * @return string   Transaction reference
     */
    public function getTxnRef() {
        return $this->txnRef;
    }

    /**
     * Your own reference ID used when making the call.
     * This will be empty if you didn't use the "tag" parameter when calling.
     *
     * @return string   Your own reference ID
     */
    public function getTag() {
        return $this->tag;
    }

    /**
     * The source number of this call.
     *
     * @return string   Source number
     */
    public function getFrom() {
        return $this->from;
    }

    /**
     * The destination number of this call.
     *
     * @return string   Destination number
     */
    public function getTo() {
        return $this->to;
    }

    /**
     * Date/time of this call (in GMT+8).
     *
     * @return string   In YYYY-MM-DD HH:MM:SS fomat
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Call duration of this call.
     *
     * @return int  Call duration in minutes
     */
    public function getDuration() {
        return $this->duration;
    }

    /**
     * Currency code that this call will be billed.
     *
     * @return string   Currency code
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
     * Total cost deducted from your account for making this call.
     *
     * @return float    Total cost deducted for this call
     */
    public function getDebit() {
        return $this->debit;
    }

    /**
     * Static method to parse JSON object into CallTransaction.
     *
     * @param object $json      JSON object from json_decode
     * @return CallTransaction  Return a CallTransaction object
     */
    public static function fromJsonObject($json) {
        $tag = property_exists($json, 'tag') ? $json->{'tag'} : '';
        $duration = property_exists($json, 'duration') ? floatval($json->{'duration'}) : 0;
        $debit = property_exists($json, 'debit') ? floatval($json->{'debit'}) : 0;

        return new CallTransaction( $json->{'txn_ref'},
                                    $json->{'call_status_dest1'},
                                    $json->{'call_status_dest2'},
                                    $json->{'date'},
                                    $json->{'dest1'},
                                    $json->{'dest2'},
                                    $duration,
                                    $json->{'currency'},
                                    floatval($json->{'rate'}),
                                    $debit,
                                    $tag);
    }

    public function __construct($txnRef, $fromStatus, $toStatus, $date, $from, $to,
                                $currency, $rate, $duration = 0, $debit = 0, $tag = '') {
        $this->txnRef = $txnRef;
        $this->fromStatus = $fromStatus;
        $this->toStatus = $toStatus;
        $this->date = $date;
        $this->from = $from;
        $this->to = $to;
        $this->tag = $tag;
        $this->currency = $currency;
        $this->rate = $rate;
        $this->duration = $duration;
        $this->debit = $debit;
    }
}
