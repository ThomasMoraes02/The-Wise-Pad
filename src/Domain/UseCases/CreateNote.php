<?php 
namespace App\Domain\UseCases;

use App\Domain\Email;
use App\Domain\Note\Note;
use App\Domain\User\User;
use App\Domain\Note\NoteRepository;
use App\Domain\User\UserRepository;
use App\Domain\Exception\NoteException;

class CreateNote implements UseCase
{
    private $noteRepository;
    private $userRepository;

    public function __construct(NoteRepository $noteRepository, UserRepository $userRepository)
    {
        $this->noteRepository = $noteRepository;
        $this->userRepository = $userRepository;
    }

    public function perform(array $noteData)
    {
        $user = $this->userRepository->getUserByEmail(new Email($noteData['email']));
        $new_note = new Note($user, $noteData['title'], $noteData['content']);

        $userNotes = $this->noteRepository->findAllNotesFrom($user->getEmail());

        array_filter($userNotes, function($note) use ($new_note){
            if($new_note->getTitle() == $note->getTitle()) {
                throw new NoteException($new_note->getTitle());
            }
        });

        $this->noteRepository->addNote($new_note);

        return;
    }
}