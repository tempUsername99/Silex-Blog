<?php

namespace Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class User
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $login;

    /** @ODM\Field(type="string") */
    private $password;

    /** @ODM\Field(type="string") */
    private $role = 'ROLE_USER';

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getLogin() { return $this->login; }
    public function setLogin($login) { $this->login = $login; }

    public function getPassword() { return $this->password; }
    public function setPassword($password) { $this->password = $password; }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role) { $this->role = $role; }

    public function getSalt()
    {
        return null;
    }

}