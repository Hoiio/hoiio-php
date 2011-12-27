<?php

/**
 * CallService class provides acccess to voice-related Hoiio API.
 * Currently, includes:
 *  - Make a 2-way callback
 *  - Make conference calls
 *  - Get call rates
 *  - Get call history
 *  - Get call status
 *  - Hangup a call
 *  - Parse call notifications
 */
class CallService extends HTTPService {
    /* endpoints for call APIs */
    const H_CALL        = 'https://secure.hoiio.com/open/voice/call';
    const H_CALL_HIST   = 'https://secure.hoiio.com/open/voice/get_history';
    const H_CALL_RATE   = 'https://secure.hoiio.com/open/voice/get_rate';
    const H_CALL_STATUS = 'https://secure.hoiio.com/open/voice/query_status';
    const H_CALL_CONF   = 'https://secure.hoiio.com/open/voice/conference';
    const H_CALL_HANGUP = 'https://secure.hoiio.com/open/voice/hangup';

    public static function call($appID, $accessToken, $from, $to, $callerID = '', $tag = '', $notifyURL = '') {
        // prepare HTTP POST variables
        $fields = array(
                            'app_id' => urlencode($appID),
                            'access_token' => urlencode($accessToken),
                            'dest1' => urlencode($from),
                            'dest2' => urlencode($to)
        );

        if($callerID != '')
            $fields['caller_id'] = urlencode($callerID);

        if($tag != '')
            $fields['tag'] = urlencode($tag);

        if($notifyURL != '')
            $fields['notify_url'] = urlencode($notifyURL);

        // do the actual post to Hoiio servers
        $result = self::doHoiioPost(self::H_CALL, $fields);

        return $result->{'txn_ref'};
    }

    public static function getRate($appID, $accessToken, $from, $to) {
        // prepare HTTP POST variables
        $fields = array(
                            'app_id' => urlencode($appID),
                            'access_token' => urlencode($accessToken),
                            'dest1' => urlencode($from),
                            'dest2' => urlencode($to)
        );

        // do the actual post to Hoiio servers
        $result = self::doHoiioPost(self::H_CALL_RATE, $fields);

        return new CallRate(    $result->{'currency'},
                                floatval($result->{'rate'}),
                                floatval($result->{'talktime'}));
    }

    public static function getStatus($appID, $accessToken, $txnRef) {
        // prepare HTTP POST variables
        $fields = array(
                            'app_id' => urlencode($appID),
                            'access_token' => urlencode($accessToken),
                            'txn_ref' => urlencode($txnRef)
        );

        // do the actual post to Hoiio servers
        $result = self::doHoiioPost(self::H_CALL_STATUS, $fields);

        return CallTransaction::fromJsonObject($result);
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
        $result = self::doHoiioPost(self::H_CALL_HIST, $fields);

        // parse result into CallTransaction objects
        $transactions = array();
        $jsonEntries = $result->{'entries'};
        foreach($jsonEntries as $v)
            array_push($transactions, CallTransaction::fromJsonObject($v));

        return new TransactionHistory($result->{'total_entries_count'}, $result->{'entries_count'}, $transactions,
                                        $resultFrom, $resultTo, $page);

    }

    public static function conference($appID, $accessToken, $dest, $room = '', $callerID = '', $tag = '', $notifyURL = '') {
        // prepare HTTP POST variables
        $fields = array(
                            'app_id' => urlencode($appID),
                            'access_token' => urlencode($accessToken),
                            'dest' => urlencode(implode(',', $dest))
        );

        if($room != '')
            $fields['room'] = urlencode($room);

        if($callerID != '')
            $fields['caller_id'] = urlencode($callerID);

        if($tag != '')
            $fields['tag'] = urlencode($tag);

        if($notifyURL != '')
            $fields['notify_url'] = urlencode($notifyURL);

        // do the actual post to Hoiio servers
        $result = self::doHoiioPost(self::H_CALL_CONF, $fields);

        // parse result into ConferenceMember objects
        $members = array();
        $jsonEntries = explode(",", $result->{'txn_refs'});
        for($i=0;$i < count($dest);$i++)
            array_push($members, new ConferenceMember($jsonEntries[$i], $dest[$i]));

        return new ConferenceRoom($result->{'room'}, $members);
    }

    public static function hangup($appID, $accessToken, $txnRef) {
        // prepare HTTP POST variables
        $fields = array(
                            'app_id' => urlencode($appID),
                            'access_token' => urlencode($accessToken),
                            'txn_ref' => urlencode($txnRef)
        );

        // do the actual post to Hoiio servers
        $result = self::doHoiioPost(self::H_CALL_HANGUP, $fields);

        return true;
    }

    public static function parseCallNotify($post_var) {
        $tag = array_key_exists('tag', $post_var) ? $post_var['tag'] : '';

        return new CallTransaction( $post_var['txn_ref'],
                                    $post_var['call_status_dest1'],
                                    $post_var['call_status_dest2'],
                                    $post_var['date'],
                                    $post_var['dest1'],
                                    $post_var['dest2'],
                                    floatval($post_var['duration']),
                                    $post_var['currency'],
                                    floatval($post_var['rate']),
                                    floatval($post_var['debit']),
                                    $tag);
    }
}
