
hoiio-php
----------------------

hoiio-php is a PHP SDK for Hoiio's Voice and SMS API. It encapsulates the REST
communications and let developers use the API via a few simple classes.

Currently, hoiio-php supports the Call, SMS, Account, Hoiio Number and IVR APIs.


Requirements
----------------------
- PHP 5 >= 5.1
- PHP JSON extension
- PHP cURL extension


Installation
----------------------
Just download and extract the package into your project folder. You should be
able to see a hoiio-php/ folder.

Or if you are using [composer](https://getcomposer.org), you can add this to your composer.json

	{
	    "require": {
	        "hoiio/hoiio-php": "*"
	    }
	}


Usage
----------------------
You will be able to access all of Hoiio's API via the HoiioService class.
Check the docs for details.

	// E.g. Sending SMS
	require 'hoiio-php/Services/HoiioService.php';

	$h = new HoiioService("myAppID", "myAccessToken");
	$txnRef = $h->sms("+651111111", "hello world");
	print("SMS sent successfully. TxnRef: $txnRef\n");

	// E.g. Building IVR
	require 'hoiio-php/Services/HoiioService.php';

	$h = new HoiioService("myAppID", "myAccessToken");
	$notify = $h->parseIVRNotify($_POST);
	$session = $notify->getSession();
	$key = $notify->getDigits();

	$h->ivrPlay($session, '', "You just pressed $key.");


License
----------------------
This project is under [MIT License](http://en.wikipedia.org/wiki/MIT_License).
See LICENSE file for details.


Contacts
----------------------
If you have any questions, please feel free to contact us:

- [@hoiio](https://twitter.com/hoiio)
- [Google Group(https://groups.google.com/forum/#!forum/hoiio-developers)
- [Facebook Page][http://www.facebook.com/Hoiio]
- [Hoiio Developer Blog](http://devblog.hoiio.com/)

