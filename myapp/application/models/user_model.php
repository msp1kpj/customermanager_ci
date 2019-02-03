<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_Model extends MY_Model
{
    public $table = 'user';
    public $primaryKey = 'pkid';

    public $pkid = 0;
    public $firstName = "";
    public $lastName = "";
    public $emailAddress = "";
    public $UserName = "";
    public $Password = "";
    public $passwordSalt = "";
    public $isActive = 0;
    public $dateCreated = "";
    public $dateLastModified = "";

    public function __toString()
    {
        return $this->UserName;
    }

    public function getName()
    {
        return $this->firstName . " " . $this->lastName;
    }

    public function validatePassword($password) {
        $validPass = false;
        $inputHash = hash("sha512", trim($password) . trim($this->passwordSalt));

        if($inputHash == $this->Password || strtoupper($inputHash) == $this->Password ){
            $validPass = true;
        }

        return $validPass;
    }

    public function newHashPassword($password) {
        $newsalt = $this->guidV4();
        $passwordHash = hash("sha512", trim($password) . trim($newsalt));

        return (object) array("salt"=>$newsalt, "passwordHash"=>$passwordHash);
    }

    /**
     * Delete
     * @return bool
     */
    public function remove()
    {
        $this->isActive = 0;
        $this->save();

        return $this->isActive == 0;
    }


    /**
     * guidV4 ()
     * -------------------------------------------------------------------
     *
     * @return string
     */
    private function guidV4()
    {
        // Microsoft guid {xxxxxxxx-xxxx-Mxxx-Nxxx-xxxxxxxxxxxx}
        if (function_exists('com_create_guid') === true)
        {
            return trim(com_create_guid(), '{}');
        }

        $data = openssl_random_pseudo_bytes(16);

        // set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);

        // set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }


}
