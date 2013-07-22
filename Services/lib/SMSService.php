<?php

/**
 * SMSService class provides acccess to SMS-related Hoiio API.
 * Currently, includes:
 *  - Send SMS
 *  - Send Bulk SMS
 *  - Get SMS rates
 *  - Get SMS history
 *  - Get SMS status
 *  - Parse SMS notifications
 */
class SMSService extends HTTPService {
    /* endpoints for SMS APIs */
    const H_SMS         = 'https://secure.hoiio.com/open/sms/send';
    const H_SMS_BULK    = 'https://secure.hoiio.com/open/sms/bulk_send';
    const H_SMS_HIST    = 'https://secure.hoiio.com/open/sms/get_history';
    const H_SMS_RATE    = 'https://secure.hoiio.com/open/sms/get_rate';
    const H_SMS_STATUS  = 'https://secure.hoiio.com/open/sms/query_status';

    public static function sms($appID, $accessToken, $to, $msg, $senderID = '', $tag = '', $notifyURL = '') {
        // prepare HTTP POST variables
        $fields = array(
                            'app_id' => urlencode($appID),
                            'access_token' => urlencode($accessToken),
                            'dest' => urlencode($to),
                            'msg' => urlencode($msg)
        );

        if($senderID != '')
            $fields['sender_name'] = urlencode($senderID);

        if($tag != '')
            $fields['tag'] = urlencode($tag);

        if($notifyURL != '')
            $fields['notify_url'] = urlencode($notifyURL);

        // do the actual post to Hoiio servers
        $result = self::doHoiioPost(self::H_SMS, $fields);

        return $result->{'txn_ref'};
    }
    
    public static function bulksms($appID, $accessToken, $to, $msg, $senderID = '', $tag = '', $notifyURL = '') {
        // prepare HTTP POST variables
        $fields = array(
                            'app_id' => urlencode($appID),
                            'access_token' => urlencode($accessToken),
                            'msg' => urlencode($msg),
                            'dest' => urlencode($to)
        );

        if($senderID != '')
            $fields['sender_name'] = urlencode($senderID);

        if($tag != '')
            $fields['tag'] = urlencode($tag);

        if($notifyURL != '')
            $fields['notify_url'] = urlencode($notifyURL);

        // do the actual post to Hoiio servers
        $result = self::doHoiioPost(self::H_SMS_BULK, $fields);

        return $result->{'bulk_txn_ref'};
    }

    public static function getRate($appID, $accessToken, $to, $msg) {
        // prepare HTTP POST variables
        $fields = array(
                            'app_id' => urlencode($appID),
                            'access_token' => urlencode($accessToken),
                            'dest' => urlencode($to),
                            'msg' => urlencode($msg)
        );

        // do the actual post to Hoiio servers
        $result = self::doHoiioPost(self::H_SMS_RATE, $fields);

        return new SMSRate( $result->{'currency'},
                            floatval($result->{'rate'}),
                            intval($result->{'split_count'}),
                            floatval($result->{'total_cost'}),
                            $result->{'is_unicode'});
    }

    public static function getStatus($appID, $accessToken, $txnRef) {
        // prepare HTTP POST variables
        $fields = array(
                            'app_id' => urlencode($appID),
                            'access_token' => urlencode($accessToken),
                            'txn_ref' => urlencode($txnRef)
        );

        // do the actual post to Hoiio servers
        $result = self::doHoiioPost(self::H_SMS_STATUS, $fields);

        return SMSTransaction::fromJsonObject($result);
    }

    public static function getHistory($appID, $accessToken, $resultFrom = NULL, $resultTo = NULL, $page = 1) {
        // prepare HTTP POST variables
        $fields = array(
                        'app_id' => urlencode($appID),
                        'access_token' => urlencode($accessToken),
                        'page' => urlencode($page)
        );

        if(!is_null($resultFrom))
            $fields['resultFrom'] = urlencode($resultFrom);

        if(!is_null($resultTo))
            $fields['resultTo'] = urlencode($resultTo);

        // do the actual post to Hoiio servers
        $result = self::doHoiioPost(self::H_SMS_HIST, $fields);

        // parse result into SMSTransaction objects
        $transactions = array();
        $jsonEntries = $result->{'entries'};
        foreach($jsonEntries as $v)
            array_push($transactions, SMSTransaction::fromJsonObject($v));

        return new TransactionHistory($result->{'total_entries_count'}, $result->{'entries_count'}, $transactions,
                                        $resultFrom, $resultTo, $page);
    }

    public static function parseSMSNotify($post_var) {
        $tag = array_key_exists('tag', $post_var) ? $post_var['tag'] : '';

        return new SMSTransaction(  $post_var['txn_ref'],
                                    $post_var['sms_status'],
                                    $post_var['date'],
                                    $post_var['dest'],
                                    $post_var['currency'],
                                    floatval($post_var['rate']),
                                    intval($post_var['split_count']),
                                    floatval($post_var['debit']),
                                    $tag);
    }
}


