<?php

class IVRNotification {
    // generic params
    private $callState = '';
    private $session = '';
    private $txnRef = '';
    private $tag = '';

    // params available only if call has ended
    private $date = '';
    private $duration = 0;
    private $currency = '';
    private $rate = 0;
    private $debit = 0;

    // params for specific blocks like Dial, Gather, Transfer
    private $dialStatus = '';        // Dial block
    private $dest = '';              // Dial block
    private $digits = '';            // Gather block
    private $recordURL = '';         // Record block
    private $transferStatus = '';    // Transfer block
    private $from = '';              // Answer block
    private $to = '';                // Answer block

    /**
     * The current state of the IVR.
     * Look at IVRCallState for possible value
     *
     * @return string   IVR call state
     */
    public function getCallState() {
        return $this->callState;
    }

    /**
     * Current session ID.
     *
     * @return string   Current session ID
     */
    public function getSession() {
        return $this->session;
    }

    /**
     * Current transaction reference
     *
     * @return string   Current transaction reference
     */
    public function getTxnRef() {
        return $this->txnRef;
    }

    /**
     * Your own reference ID
     *
     * @return string   Your own reference ID
     */
    public function getTag() {
        return $this->tag;
    }

    /**
     * Date of this IVR session
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
     * The dial status of the number after using a Dial block.
     * Look at CallStatus class for possible values.
     *
     * @return string   Dial Status (Dial block)
     */
    public function getDialStatus() {
        return $this->dialStatus;
    }

    /**
    * The destination number when using a Dial block.
    *
    * @return string   Destination Number (Dial block)
    */
    public function getDest() {
        return $this->dest;
    }

    /**
     * The keypad input from the user after using the Gather block.
     *
     * @return string   Keypad input from user (Gather block)
     */
    public function getDigits() {
        return $this->digits;
    }

    /**
     * The URL of the recording after using the Record block.
     *
     * @return string   URL of recording (Record block)
     */
    public function getRecordURL() {
        return $this->recordURL;
    }

    /**
     * The transfer status of the number after using a Transfer block.
     * Look at CallStatus class for possible values.
     *
     * @return string   Transfer Status (Transfer block)
     */
    public function getTransferStatus() {
        return $this->transferStatus;
    }

    /**
     * The incoming Caller ID to your Hoiio Number.
     *
     * @return string   Incoming Caller ID (Answer block)
     */
    public function getFrom() {
        return $this->from;
    }

    /**
     * The Hoiio Number that was dialed for incoming call.
     *
     * @return string    Dialed Hoiio Number (Answer block)
     */
    public function getTo() {
        return $this->to;
    }

    public function __construct($callState, $session, $txnRef,
                                    $dialStatus = '', $digits = '', $recordURL = '', $transferStatus = '',
                                    $from = '', $to = '', $dest = '',
                                    $date = '', $currency = '', $rate = 0, $duration = 0, $debit = 0,
                                    $tag = '') {
        $this->callState = $callState;
        $this->session = $session;
        $this->txnRef = $txnRef;
        $this->tag = $tag;

        $this->dialStatus = $dialStatus;
        $this->digits = $digits;
        $this->recordURL = $recordURL;
        $this->transferStatus = $transferStatus;
        $this->from = $from;
        $this->to = $to;
        $this->dest = $dest;

        $this->date = $date;
        $this->duration = $duration;
        $this->currency = $currency;
        $this->rate = $rate;
        $this->debit = $debit;
    }
}
