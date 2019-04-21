<?php


namespace Amadeus\Network\Verification;


use Amadeus\Config\Config;

/**
 * Class API
 * @package Amadeus\Network\Verification
 */
class API
{
    /**
     * @param $request
     * @return bool
     */
    public static function isOkay($request)
    {
        if (!($request = self::unpackData($request->data, true))) {
            return false;
        }
        if (empty($request['action']) || empty($request['message'])) {
            return false;
        }
        if (@$request['message']['api'] > Config::get('daemon_api_version')) {
            return true;
        }
        return false;
    }

    /**
     * @param $data
     * @param $assoc
     * @return bool|mixed
     */
    public static function unpackData($data, $assoc)
    {
        $data = json_decode($data, $assoc);
        if ($data && (is_object($data)) || (is_array($data) && !empty(current($data)))) {
            if (!empty($data['action']) and !empty($data['message'])) {
                return $data;
            }
            return false;
        }
        return false;
    }
}