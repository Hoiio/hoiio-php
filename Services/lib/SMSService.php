<?php

/*
 * SMSService class provides acccess to SMS-related Hoiio API.
 * Currently, includes:
 * 	- SMS
 * 	- Get SMS rates
 * 	- Get SMS history
 * 	- Get SMS status
 */
class SMSService extends HTTPService {
	/* endpoints for SMS APIs */
	const H_SMS			= 'https://secure.hoiio.com/open/sms/send';
	const H_SMS_HIST	= 'https://secure.hoiio.com/open/sms/get_history';
	const H_SMS_RATE	= 'https://secure.hoiio.com/open/sms/get_rate';
	const H_SMS_STATUS	= 'https://secure.hoiio.com/open/sms/query_status';
	
	/* Send a SMS 
	 * 
	 * @return	string			Transaction Reference
	 * @throws	HoiioException	Error calling Hoiio API
	 */
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
	
	/*
	 * Query the rate for sending a SMS
	 * 
	 * @return	SMSRate			Look at SMSRate class for available information.
	 * @throws	HoiioException	Error calling Hoiio API.
	 */
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
		
		return new SMSRate(	$result->{'currency'}, 
							floatval($result->{'rate'}), 
							intval($result->{'split_count'}), 
							floatval($result->{'total_cost'}),
							$result->{'is_unicode'});	
	}
	
	/*
	 * Query the status of a SMS sent previously.
	 * 
	 * The status will change from "queued" to "delivered"/"failed"
	 * after some time so you may need to query this a few times.
	 * 
	 * @return	SMSTransaction	Look at SMSTransaction for available information.
	 * @throws	HoiioException	Error calling Hoiio API.
	 */
	public static function getStatus($appID, $accessToken, $txnRef) {
		// prepare HTTP POST variables
		$fields = array(
							'app_id' => urlencode($appID),
							'access_token' => urlencode($accessToken),
							'txn_ref' => urlencode($txnRef)						
		);
		
		// do the actual post to Hoiio servers
		$result = self::doHoiioPost(self::H_SMS_STATUS, $fields);
		
		$tag = property_exists($result, 'tag') ? $result->{'tag'} : '';
		return new SMSTransaction(	$result->{'txn_ref'},
									$result->{'sms_status'}, 
									$result->{'date'}, 
									$result->{'dest'}, 									
									$result->{'currency'},
									floatval($result->{'rate'}),
									intval($result->{'split_count'}),
									floatval($result->{'debit'}),
									$tag);		
	}		
	
	/*
	 * Query the list of SMS transaction history.
	 * 
	 * @return	SMSHistory		Look at SMSHistory for available information.
	 * @throws	HoiioException
	 */
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
		foreach($jsonEntries as $v) {
			$tag = property_exists($v, 'tag') ? $v->{'tag'} : '';
			$t = new SMSTransaction(	$v->{'txn_ref'}, 
										$v->{'sms_status'}, 
										$v->{'date'}, 
										$v->{'dest'},  
										$v->{'currency'}, 
										floatval($v->{'rate'}), 
										intval($v->{'split_count'}), 
										floatval($v->{'debit'}), 
										$tag);
			
			array_push($transactions, $t);
		}
		
		return new SMSHistory($result->{'total_entries_count'}, $result->{'entries_count'}, $transactions,
								$resultFrom, $resultTo, $page);					
	}
	
	/*
	 * Parse the SMS notifications sent to you. Multiple notifications 
	 * may be recieved over time. You can check the status of the SMS 
	 * delivery using this.
	 * 
	 * Pass in your $_POST variable from your notify_url.
	 * 
	 * @return	SMSTransaction	Look at SMSTransaction for available information.
	 */
	public static function parseSMSNotify($post_var) {
		$tag = array_key_exists('tag', $post_var) ? $post_var['tag'] : '';
		
		return new SMSTransaction(	$post_var['txn_ref'], 
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