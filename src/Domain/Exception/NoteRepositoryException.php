<?php 
namespace App\Domain\Exception;

use DomainException;

class NoteRepositoryException extends DomainException
{
    public function __construct()
    {
        parent::__construct("Note not found");
    }
}