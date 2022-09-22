<?php 
namespace App\Domain\UseCases;

use App\Domain\Email;
use App\Domain\Note\Note;
use App\Domain\UseCases\UseCase;
use App\Domain\Note\NoteRepository;
use App\Domain\User\UserRepository;
use App\Domain\Exception\NoteException;

class UpdateNote implements UseCase
{
    private $noteRepository;
    private $userRepository;

    public function __construct(NoteRepository $noteRepository, UserRepository $userRepository)
    {
        $this->noteRepository = $noteRepository;
        $this->userRepository = $userRepository;
    }

    public function perform(array $updateRequest)
    {
        $user = $this->userRepository->getUserByEmail(new Email($updateRequest['email']));
        $note = $this->noteRepository->getNoteById($updateRequest['id']);

        $noteTitle = $this->shouldChangeTitle($note->getTitle(), $updateRequest['title']);
        $noteContent = $this->shouldChangeContent($note->getContent(), $updateRequest['content']);

        if($noteTitle == true) {
            $this->verifyTitleAlreadyExists($note, $updateRequest['title']);
            $this->noteRepository->updateTitle($updateRequest['id'], $updateRequest['title']);
        }

        if($noteContent == true) {
            $this->noteRepository->updateContent($updateRequest['id'], $updateRequest['content']);
        }

        $noteUpdated = $this->noteRepository->getNoteById($updateRequest['id']);

        return $noteUpdated;
    }

    private function verifyTitleAlreadyExists(Note $note, string $new_title): void
    {
        $userNotes = $this->noteRepository->findAllNotesFrom($note->getUser()->getEmail());

        array_filter($userNotes, function($note) use ($new_title){
            if($note->getTitle() == $new_title) {
                throw new NoteException($note->getTitle());
            }
        });
    }

    private function shouldChangeTitle(string $oldTitle, string $newTitle): bool
    {
        $title = ($oldTitle != $newTitle) ? true : false;
        $title = empty($newTitle) ? false : true;

        return $title;
    }

    private function shouldChangeContent(string $oldContent, string $newContent): bool
    {
        $content = ($oldContent != $newContent) ? true : false;
        $content = empty($newContent) ? false : true;

        return $content;
    }
}