<?php 
namespace App\Domain;

use App\Domain\Exception\EmailException;

class Email
{
    private $email;

    public function __construct(string $email)
    {
        $this->setEmail($email);
    }

    private function setEmail(string $email): void
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
            throw new EmailException("E-mail $email invalid");
        }

        $this->email = $email;
    }

    public function __toString()
    {
        return $this->email;
    }
}