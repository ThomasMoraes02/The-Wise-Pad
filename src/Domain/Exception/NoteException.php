<?php 
namespace App\Domain\Exception;

use DomainException;

class NoteException extends DomainException
{
    public function __construct(string $text)
    {
        parent::__construct("Note with title: $text exists");
    }
}