<?php

namespace ApiBundle\dto;



class UserDto
{
    /**
     * @var string
     */
    private $username;

    
    /**
     * @var string
     */
    private $phone;


     public function __construct($username,$phone) {
        $this->username = $username;
        $this->phone = $phone;
    }

    


    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

}
