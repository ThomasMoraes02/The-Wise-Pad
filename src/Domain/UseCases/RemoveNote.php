<?php 
namespace App\Domain\UseCases;

use App\Domain\Note\NoteRepository;

class RemoveNote implements UseCase
{
    private $noteRepository;

    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    public function perform(array $data)
    {
        $note = $this->noteRepository->getNoteById($data['id']);

        if(!empty($note)) {
            $this->noteRepository->removeNote($data['id']);
        }
    }
}