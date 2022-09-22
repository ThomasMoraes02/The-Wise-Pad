<?php 
namespace App\Domain\Exception;

use App\Domain\Email;
use DomainException;

class UserRepositoryException extends DomainException
{
    public function __construct(string $text)
    {
        parent::__construct("User with e-mail $text not found");
    }
}