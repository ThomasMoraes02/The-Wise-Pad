<?php 
namespace App\Domain\UseCases;

use App\Domain\Email;
use App\Domain\UseCases\UseCase;
use App\Domain\Note\NoteRepository;

class LoadNotes implements UseCase
{
    private $noteRepository;

    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    public function perform($userData)
    {
        return $this->noteRepository->findAllNotesFrom(new Email($userData['email']));
    }
}