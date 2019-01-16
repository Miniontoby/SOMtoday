<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 16-1-19
 * Time: 18:57
 */

namespace SOMtodayAPI\SOMtodayAPI\Services;


use Curl\Curl;
use SOMtodayAPI\SOMtodayAPI\Exceptions\RequestException;

class Request {

    private static $_token = null;
    private static $_baseURL = null;

    public static function setToken($token) {
        self::$_token = $token;
    }
    public static function setBaseURL($baseURL) {
        self::$_baseURL = $baseURL;
    }

    private static function init() {
        if(is_null(self::$_baseURL)) {
            throw new RequestException('No baseURL has been set.');
        }
        if(is_null(self::$_token)) {
            throw new RequestException('No access_token has been set.');
        }
        $curl = new Curl();
        $curl->setHeaders([
            'Accept' => 'application/json',
            'Authorization' => self::$_token
        ]);
        return $curl;
    }

    public static function get($uri) {
        $curl = self::init();
        $curl->get(self::$_baseURL . $uri);
        if(isset($curl->response->error)) {
            throw new RequestException($curl->response->error_description);
        }
        if ($curl->error) {
            throw new RequestException('cURL Error: ' . $curl->errorCode . ': ' . $curl->errorMessage);
        }
        return $curl->response;
    }

    public static function getOrdinary($url) {
        $curl = new Curl();
        $curl->get($url);
        if ($curl->error) {
            throw new RequestException('cURL Error: ' . $curl->errorCode . ': ' . $curl->errorMessage);
        }
        return $curl->response;
    }
}