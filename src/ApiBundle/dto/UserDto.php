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


    /**
     * @var string
     */
    private $firstname;

    /**
     * @var string
     */
    private $lastname;

     /**
     * @var string
     */
    private $address;


     public function __construct($username,$phone,$firstname,$lastname,$address) {
        $this->username = $username;
        $this->phone = $phone;
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->address = $address;
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


    /**
     * Get the value of lastname
     *
     * @return  string
     */ 
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @param  string  $lastname
     *
     * @return  self
     */ 
    public function setLastname(string $lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get the value of firstname
     *
     * @return  string
     */ 
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @param  string  $firstname
     *
     * @return  self
     */ 
    public function setFirstname(string $firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get the value of address
     *
     * @return  string
     */ 
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the value of address
     *
     * @param  string  $address
     *
     * @return  self
     */ 
    public function setAddress(string $address)
    {
        $this->address = $address;

        return $this;
    }
}
