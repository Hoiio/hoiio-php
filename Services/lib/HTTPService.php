<?php

/**
 * HTTPService class to help in HTTP-related tasks.
 *
 */
class HTTPService {
    /**
     * Send the actual HTTP POST to Hoiio servers.
     *
     * @return mixed            Returns the result from json_decode of Hoiio's response
     * @throws HoiioException   Error sending HTTP POST
     */
    protected static function doHoiioPost($url, $fields) {
        // form up variables in the correct format for HTTP POST
        $fields_string = '';
        foreach($fields as $key => $value)
        $fields_string .= $key . '=' . $value . '&';

        $fields_string = rtrim($fields_string,'&');

        // initialize cURL
        $ch = curl_init();

        // set options for cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

        // execute HTTP POST request
        $response = curl_exec($ch);

        // close connection
        curl_close($ch);

        return self::parseResult($response);
    }

    /**
     * Throw HoiioException if there are errors.
     *
     * @return mixed            Returns the result from json_decode of Hoiio's response
     * @throws HoiioException   Error sending HTTP POST
     */
    private static function parseResult($response) {
        $result = json_decode($response);

        if($result->{'status'} != HoiioException::SUCCESS)
            throw new HoiioException($result->{'status'});

        return $result;
    }
}
