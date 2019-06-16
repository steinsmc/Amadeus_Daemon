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
     * 0 : guest
     * 1 : serverOwner
     * 2 : co-administrator
     * 3 : administrator
     * 4 : rdms
     */
    private
        $fd,
        $ip,
        $type,
        $status = false;

    public function __construct(int $fd, string $ip)
    {
        $this->fd = $fd;
        $this->ip = $ip;
    }

    public function setType(int $type): bool
    {
        if (empty($this->type)) {
            $this->type = $type;
            return true;
        }
        return false;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setStatus(bool $status): bool
    {
        if($this->status!=$status){
            $this->status=$status;
            return true;
        }
        return false;
    }

    public function getStatus(): bool
    {
        return $this->status;
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