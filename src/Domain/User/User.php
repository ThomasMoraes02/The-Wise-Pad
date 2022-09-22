<?php 
namespace App\Domain\User;

use App\Domain\Email;
use App\Domain\PasswordHash;

class User
{
    private $name;
    private $email;
    private $password;

    public function __construct(string $name, Email $email, PasswordHash $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public static function create(string $name, string $email, PasswordHash $password)
    {
        return new User($name, new Email($email), $password);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }
}