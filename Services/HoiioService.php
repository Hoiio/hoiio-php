<?php

function __autoload($class_name) {
    require_once('lib/'. $class_name. '.php');
}

/*
 * Public interface to access Hoiio API.
 * Use this class to do your stuffs.
 * 
 * E.g.
 * $h = new HoiioService("myAppID", "myAccessToken"); 
 * $txnRef = $h->sms("+651111111", "hello world"); 
 * print("SMS sent successfully. TxnRef: $txnRef\n");  
 * 
 */
class HoiioService {        
    /* developer credentials */
    private $appID = '';
    private $accessToken = '';
    
    public function __construct($appID, $accessToken) {
        $this->appID = $appID;
        $this->accessToken = $accessToken;
    }
        
    /***********************************
     * SMS-related methods 
     ***********************************/
        
    /*
     * Use this to send a SMS
     * 
     * @return  string          Transaction Reference
     * @throws  HoiioException  Error sending SMS
     */ 
    public function sms($to, $msg, $senderID = '', $tag = '', $notifyURL = '') {
        return SMSService::sms($this->appID, $this->accessToken, $to, $msg, $senderID, $tag, $notifyURL);
    }   
    
    /*
     * Use this to query the cost of sending a SMS before actually sending it.
     * 
     * @return  SMSRate         Look at SMSRate class for available information.
     * @throws  HoiioException  Error querying rate for this SMS.
     */
    public function getSMSRate($to, $msg) {
        return SMSService::getRate($this->appID, $this->accessToken, $to, $msg);        
    }
    
    /*
     * Use this to query the current status of a SMS sent previously.
     * 
     * The status will change from "queued" to "delivered"/"failed"
     * after some time so you may need to query this a few times.
     * 
     * @return  SMSTransaction  Look at SMSTransaction for available information.
     * @throws  HoiioException  Error querying status of this SMS.
     */
    public function getSMSStatus($txnRef) {
        return SMSService::getStatus($this->appID, $this->accessToken, $txnRef);
    }

    /*
    * Use this to query the list of SMS transaction history.
    *
    * @return   SMSHistory      Look at SMSHistory class for available information.
    * @throws   HoiioException  Error querying status of this SMS.
    */  
    public function getSMSHistory($resultFrom = NULL, $resultTo = NULL, $page = 1) {
        return SMSService::getHistory($this->appID, $this->accessToken,
                                        $resultFrom, $resultTo, $page);
    }
    
    /*
    * Parse the SMS notifications sent to you. Multiple notifications
    * may be recieved over time. You can check the status of the SMS
    * delivery using this.
    *
    * Pass in your $_POST variable from your notify_url.
    *
    * @return   SMSTransaction  Look at SMSTransaction for available information.
    */
    public function getSMSHistory($post_vars) {
        return SMSService::parseSMSNotify($post_var);
    }   
}