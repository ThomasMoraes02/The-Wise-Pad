<?php 
namespace App\Domain\Exception;

use DomainException;

class EmailException extends DomainException
{
    public function __construct(string $email)
    {
        parent::__construct("Invalid e-mail: $email");
    }
}