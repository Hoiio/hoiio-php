<?php

function __autoload($class_name) {
    require_once('lib/'. $class_name. '.php');
}

/**
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
    * Call-related methods
    ***********************************/

    /**
     * Use this to make a 2-way callback.
     *
     * @param string $from      Source number
     * @param string $to        Destination number
     * @param string $tag       Your own reference ID
     * @param string $notifyURL Your URL to recieve call notification
     *
     * @return string           Transaction Reference
     * @throws HoiioException   Error sending call to Hoiio API
     */
    public function call($from, $to, $tag = '', $notifyURL = '') {
        return CallService::call($this->appID, $this->accessToken, $from, $to, $tag, $notifyURL);
    }

    /**
     * Use this to query the cost of making a call before actually sending it.
     *
     * @param string $from      Source number
     * @param string $to        Destination number
     *
     * @return CallRate         Look at CallRate class for available information
     * @throws HoiioException   Error querying rate for this call
     */
    public function getCallRate($from, $to) {
        return CallService::getRate($this->appID, $this->accessToken, $from, $to);
    }

    /**
     * Use this to query the current status of a call made previously.
     * The status will be "ongoing" if the call is still alive.
     *
     * @param string $txnRef    Transaction Reference
     *
     * @return CallTransaction  Look at CallTransaction for available information
     * @throws HoiioException   Error querying status of this call
     */
    public function getCallStatus($txnRef) {
        return CallService::getStatus($this->appID, $this->accessToken, $txnRef);
    }

    /**
     * Use this to query the list of call transaction history.
     *
     * @param string $resultFrom    Start date for query in YYYY-MM-DD HH:MM:SS fomat
     * @param string $resultTo      End date for query in YYYY-MM-DD HH:MM:SS fomat
     * @param int $page             Which page of result to query (starts from 1)
     *
     * @return TransactionHistory   Look at TransactionHistory class for available information
     * @throws HoiioException       Error querying call history
     */
    public function getCallHistory($resultFrom = NULL, $resultTo = NULL, $page = 1) {
        return CallService::getHistory($this->appID, $this->accessToken, $resultFrom, $resultTo, $page);
    }

    /**
     * Parse the call notifications sent to you when the call ends. Multiple
     * notifications may be recieved over time.
     *
     * @param array $post_vars  Pass in the system variable $_POST
     *
     * @return CallTransaction  Look at CallTransaction for available information
     */
    public function parseCallNotify($post_vars) {
        return CallService::parseCallNotify($post_var);
    }


    /***********************************
     * SMS-related methods
     ***********************************/

    /**
     * Use this to send a SMS.
     *
     * @param string $to        Destination number
     * @param string $msg       SMS message
     * @param string $senderID  Sender ID (you need to request from Hoiio for this feature)
     * @param string $tag       Your own reference ID
     * @param string $notifyURL Your URL to recieve call notification
     *
     * @return string           Transaction Reference
     * @throws HoiioException   Error sending SMS to Hoiio API
     */
    public function sms($to, $msg, $senderID = '', $tag = '', $notifyURL = '') {
        return SMSService::sms($this->appID, $this->accessToken, $to, $msg, $senderID, $tag, $notifyURL);
    }

    /**
     * Use this to query the cost of sending a SMS before actually sending it.
     *
     * @param string $to        Destination number
     * @param string $msg       SMS message
     *
     * @return SMSRate          Look at SMSRate class for available information
     * @throws HoiioException   Error querying rate for this SMS
     *
     */
    public function getSMSRate($to, $msg) {
        return SMSService::getRate($this->appID, $this->accessToken, $to, $msg);
    }

    /**
     * Use this to query the current status of a SMS sent previously.
     *
     * The status will change from "queued" to "delivered"/"failed"
     * after some time so you may need to query this a few times.
     *
     * @param string $txnRef    Your own reference ID
     *
     * @return SMSTransaction   Look at SMSTransaction for available information
     * @throws HoiioException   Error querying status of this SMS
     */
    public function getSMSStatus($txnRef) {
        return SMSService::getStatus($this->appID, $this->accessToken, $txnRef);
    }

    /**
     * Use this to query the list of SMS transaction history.
     *
     * @param string $resultFrom    Start date for query in YYYY-MM-DD HH:MM:SS fomat
     * @param string $resultTo      End date for query in YYYY-MM-DD HH:MM:SS fomat
     * @param string $page          Which page of result to query (starts from 1)
     *
     * @return TransactionHistory   Look at TransactionHistory class for available information.
     * @throws HoiioException       Error querying SMS history
     */
    public function getSMSHistory($resultFrom = NULL, $resultTo = NULL, $page = 1) {
        return SMSService::getHistory($this->appID, $this->accessToken, $resultFrom, $resultTo, $page);
    }

    /**
     * Parse the SMS notifications sent to you. Multiple notifications
     * may be recieved over time. You can check the status of the SMS
     * delivery using this.
     *
     * @param array $post_vars  Pass in the system variable $_POST
     *
     * @return SMSTransaction   Look at SMSTransaction for available information
     */
    public function parseSMSNotify($post_vars) {
        return SMSService::parseSMSNotify($post_var);
    }
}

