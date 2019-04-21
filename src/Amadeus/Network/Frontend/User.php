<?php


namespace Amadeus\Network\Frontend;


/**
 * Class User
 * @package Amadeus\Network\Frontend
 */
class User
{
    /**
     * @var
     */
    /**
     * @var
     */
    private
        $fd,
        $ip;

    /**
     * User constructor.
     * @param $fd
     * @param $ip
     */
    public function __construct($fd, $ip)
    {
        $this->fd = $fd;
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getFd()
    {
        return $this->fd;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @return array
     */
    public function getUser()
    {
        return array(
            'fd' => $this->fd,
            'ip' => $this->ip
        );
    }
}