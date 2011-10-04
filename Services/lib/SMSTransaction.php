<?php

/*
 * Represents a SMS Transaction.
 */
class SMSTransaction {
    private $smsStatus = '';
    private $txnRef = '';
    private $tag = '';
    private $dest = '';
    private $date = '';
    private $splitCount = 0;
    private $currency = '';
    private $rate = 0;
    private $debit = 0;
    
    /*
     * The current status of this SMS. Look at SMSStatus class for possible values.
     * 
     * @return  string
     */
    public function getSMSStatus() {
        return $this->smsStatus;
    }
    
    /*
     * Transaction Reference of this SMS.
     * 
     * @return  string
     */
    public function getTxnRef() {
        return $this->txnRef;
    }
    
    /*
     * Your own reference ID used when sending the SMS.
     * This will be empty if you didn't use the "tag" parameter when sending.
     * 
     * @return  string
     */
    public function getTag() {
        return $this->tag;
    }
    
    /*
     * The number this SMS was sent to.
     * 
     * @return  string
     */
    public function getDest() {
        return $this->dest;
    }
    
    /* 
     * Date/time of this SMS (in GMT+8).     
     * 
     * @return  string
     */
    public function getDate() {
        return $this->date;
    }
    
    /*
     * How many SMS was required to send this message.
     * 
     * @return  int
     */
    public function getSplitCount() {
        return $this->splitCount;
    }
    
    /*
    * Currency code that this SMS will be billed.
    * 
    * @return   string
    */
    public function getCurrency() {
        return $this->currency;
    }
    
    /*
    * Cost per SMS (in currency indicated in currency code).
    * 
    * @return   float
    */
    public function getRate() {
        return $this->rate;
    }
    
    /*
     * Total cost deducted from your account for sending this SMS.
     * 
     * @return  float
     */
    public function getDebit() {
        return $this->debit;
    }
        
    public function __construct($txnRef, $smsStatus, $date, $dest, $currency, 
                                    $rate, $splitCount, $debit, $tag = '') {
        $this->txnRef = $txnRef;
        $this->smsStatus = $smsStatus;
        $this->date = $date;
        $this->dest = $dest;
        $this->tag = $tag;
        $this->currency = $currency;
        $this->rate = $rate;
        $this->splitCount = $splitCount;
        $this->debit = $debit;
    }   
}
