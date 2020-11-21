<?php

namespace App\Models;

class User
{
    public function __construct($cpf, $name, $email, $phone)
    {
        $this->cpf = $cpf;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
    }
}
