<?php

class class_user {
    public $id;
    public $name;
    public $email;
    public $password;
    public $role_id;
    public $role_name;


    public function __construct(
        $id = null,
        $name = null,
        $email = null,
        $password = null,
        $role_id = null,
        $role_name = null
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role_id = $role_id;
        $this->role_name = $role_name;
    }

}

?>