<?php


namespace Amadeus\Network\Verification;


use Amadeus\Config\Config;

class API
{
    public static function isOkay($request){
        if(!($request=self::unpackData($request->data,true))){
            return false;
        }
        if(empty($request['action']) || empty($request['message'])){
            return false;
        }
        if(@$request['message']['api'] > Config::get('daemon_api_version')){
            return true;
        }
        return false;
    }
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