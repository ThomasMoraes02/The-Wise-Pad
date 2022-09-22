<?php 
namespace App\Infraestructure\Note;

use App\Domain\Email;
use App\Domain\Note\Note;
use App\Domain\Note\NoteRepository;
use App\Domain\Exception\NoteRepositoryException;

class NoteRepositoryMemory implements NoteRepository
{
    private $notes = [];

    public function addNote(Note $note): void
    {
        $this->notes[] = $note;
    }

    public function getNoteById(string $id): Note
    {
        $note = array_filter($this->notes, function($note) use ($id) {
            return $note == $id;
        }, ARRAY_FILTER_USE_KEY);

        if(empty($note)) {
            throw new NoteRepositoryException();
        }

        $note = current($note);

        return new Note($note->getUser(), $note->getTitle(), $note->getContent());
    }

    public function updateTitle(string $id, string $title): void
    {
        $note = array_filter($this->notes, function($note) use ($id) {
            return $note == $id;
        }, ARRAY_FILTER_USE_KEY);

        $note = current($note);

        $note->setTitle($title);
    }

    public function updateContent(string $id, string $content): void
    {
        $note = array_filter($this->notes, function($note) use ($id) {
            return $note == $id;
        }, ARRAY_FILTER_USE_KEY);

        $note = current($note);

        $note->setContent($content);
    }

    public function removeNote(string $id): void
    {
        $note = array_filter($this->notes, function($note) use ($id) {
            return $note == $id;
        }, ARRAY_FILTER_USE_KEY);

        if(empty($note)) {
            throw new NoteRepositoryException();
        }

        unset($this->notes[$id]);
    }

    public function findAllNotesFrom(Email $email): array
    {
        $findNotes = [];
        $findNotes = array_filter($this->notes, function($note) use ($email) {
            if($note->getUser()->getEmail() == $email) {
                return $note;
            }
        });

        return $findNotes;
    }
}