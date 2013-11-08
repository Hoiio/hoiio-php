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
     * Use this to make a conference call.
     *
     * @param array  $dest      Array of destination number strings
     * @param string $room      Conference Room ID
     * @param string $tag       Your own reference ID
     * @param string $notifyURL Your URL to recieve call notification
     *
     * @return ConferenceRoom   Look at ConferenceRoom for available information
     * @throws HoiioException   Error sending call to Hoiio API
     */
    public function conference($dest, $room = '', $tag = '', $notifyURL = '') {
        return CallService::conference($this->appID, $this->accessToken, $dest, $room, $tag, $notifyURL);
    }

    /**
     * Use this to hang up a call that is currently in progress.
     *
     * @param array  $txnRef    Transaction Reference
     *
     * @return bool   			Always return true
     * @throws HoiioException   Error sending call to Hoiio API
     */
    public function hangup($txnRef) {
        return CallService::hangup($this->appID, $this->accessToken, $txnRef);
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
     * Use this to send a Bulk SMS.
     *
     * @param string $to        List of phone numbers separated by commas. Max 1000 numbers. Numbers should start with a "+" and country code
     * @param string $msg       SMS message
     * @param string $senderID  Sender ID (you need to request from Hoiio for this feature)
     * @param string $tag       Your own reference ID
     * @param string $notifyURL Your URL to recieve call notification
     *
     * @return string           Transaction Reference
     * @throws HoiioException   Error sending SMS to Hoiio API
     */
    public function bulksms($to, $msg, $senderID = '', $tag = '', $notifyURL = '') {
        return SMSService::bulksms($this->appID, $this->accessToken, $to, $msg, $senderID, $tag, $notifyURL);
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


    /***********************************
    * IVR-related methods
    ***********************************/

    /**
     * Use this to dial out to a number to start a IVR session.
     *
     * @param string $to        Destination number to dial to
     * @param string $notifyURL Your URL to recieve IVR notification
     * @param string $msg       The voice message you want to play
     * @param string $callerID  The Caller ID you want to use for this call
     * @param string $tag       Your own reference ID
     *
     * @return array            Return associative array with keys 'session' and 'txnRef'
     * @throws HoiioException   Error sending IVR Dial to Hoiio API
     */
    public function ivrDial($to, $notifyURL = '', $msg = '', $callerID = '', $tag = '') {
        return IVRService::dial($this->appID, $this->accessToken, $to, $notifyURL, $msg, $callerID, $tag);
    }

    /**
     * Use this to play a voice message in your IVR session.
     *
     * @param string $session   Session ID for this IVR
     * @param string $notifyURL Your URL to recieve IVR notification
     * @param string $msg       The voice message you want to play
     * @param string $tag       Your own reference ID
     *
     * @return bool             Always return true
     * @throws HoiioException   Error sending IVR Play to Hoiio API
     */
    public function ivrPlay($session, $notifyURL = '', $msg = '', $tag = '') {
        return IVRService::play($this->appID, $this->accessToken, $session, $notifyURL, $msg, $tag);
    }

    /**
     * Use this to gather keypad input from user in your IVR session.
     *
     * @param string $session   Session ID for this IVR
     * @param string $notifyURL Your URL to recieve IVR notification
     * @param string $msg       The voice message you want to play
     * @param string $maxDigits Maximum number of user input
     * @param string $timeout   Maximum time allowed for user to enter their input
     * @param string $attempts  How many times the user is allowed to input
     * @param string $tag       Your own reference ID
     *
     * @return bool             Always return true
     * @throws HoiioException   Error sending IVR Gather to Hoiio API
     */
    public function ivrGather($session, $notifyURL, $msg = '', $maxDigits = 0, $timeout = 15, $attempts = 1, $tag = '') {
        return IVRService::gather($this->appID, $this->accessToken, $session, $notifyURL, $msg,
                                    $maxDigits, $timeout, $attempts, $tag);
    }

    /**
     * Use this to record voice message over the phone from user in your IVR session.
     *
     * @param string $session       Session ID for this IVR
     * @param string $notifyURL     Your URL to recieve IVR notification
     * @param string $msg           The voice message you want to play
     * @param string $maxDuration   Maximum duration of the recording in seconds
     * @param string $tag           Your own reference ID
     *
     * @return bool             Always return true
     * @throws HoiioException   Error sending IVR Record to Hoiio API
     */
    public function ivrRecord($session, $notifyURL, $msg = '', $maxDuration = 120, $tag = '') {
        return IVRService::record($this->appID, $this->accessToken, $session, $notifyURL, $msg, $maxDuration, $tag);
    }

    /**
     * Use this to monitor the conversation
     *
     * @param string $session       Session ID for this IVR
     * @param string $notifyURL     Your URL to recieve IVR notification
     * @param string $msg           The voice message you want to play
     * @param string $tag           Your own reference ID
     *
     * @return bool             Always return true
     * @throws HoiioException   Error sending IVR Record to Hoiio API
     */
    public function ivrMonitor($session, $notifyURL, $msg = '', $tag = '') {
        return IVRService::monitor($this->appID, $this->accessToken, $session, $notifyURL, $msg, $tag);
    }

    /**
     * Use this to transfer a current IVR session to another number or a conference room.
     *
     * @param string $session   Session ID for this IVR
     * @param string $to        Destination number to transfer to
     * @param string $notifyURL Your URL to recieve IVR notification
     * @param string $msg       The voice message you want to play before transferring
     * @param string $callerID  The Caller ID you want to use when transferring the call
     * @param string $tag       Your own reference ID
     * @param string $onFailure Set to 'continue' if you want to handle when transfer failed. Else it will hangup.
     *
     * @return bool/string      Always return true (when transferring to E.164 number). Return Room ID when transferring to conference room.
     * @throws HoiioException   Error sending IVR Transfer to Hoiio API
     */
    public function ivrTransfer($session, $to, $notifyURL = '', $msg = '', $callerID = '', $tag = '', $onFailure = '') {
        return IVRService::transfer($this->appID, $this->accessToken, $session, $to, $notifyURL, $msg, $callerID, $tag, $onFailure);
    }

    /**
    * Use this to hang up the current IVR session.
    *
    * @param string $session    Session ID for this IVR
    * @param string $notifyURL  Your URL to recieve IVR notification
    * @param string $msg        The voice message you want to play before hanging up
    * @param string $tag        Your own reference ID
    *
    * @return bool              Always return true
    * @throws HoiioException    Error sending IVR Hangup to Hoiio API
    */
    public function ivrHangup($session, $notifyURL = '', $msg = '', $tag = '') {
        return IVRService::hangup($this->appID, $this->accessToken, $session, $notifyURL, $msg, $tag);
    }


    /**
     * Parse the IVR notifications sent to you.
     *
     * @param array $post_vars  Pass in the system variable $_POST
     *
     * @return IVRNotification  Look at IVRNotification for available information
     */
    public function parseIVRNotify($post_vars) {
        return IVRService::parseIVRNotify($post_vars);
    }
}

