<?php


namespace Amadeus\Network\Frontend;


class User
{
    private
        $fd,
        $ip;

    public function __construct($fd, $ip)
    {
        $this->fd=$fd;
        $this->ip=$ip;
    }
    public function getFd(){
        return $this->fd;
    }
    public function getIp(){
        return $this->ip;
    }
    public function getUser(){
        return array(
            'fd'=>$this->fd,
            'ip'=>$this->ip
        );
    }
}