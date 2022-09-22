<?php 
namespace App\Domain\Note;

use App\Domain\Email;

interface NoteRepository
{
    public function addNote(Note $note): void;

    public function getNoteById(string $id): Note;

    public function updateTitle(string $id, string $title): void;

    public function updateContent(string $id, string $content): void;

    public function removeNote(string $id): void;

    public function findAllNotesFrom(Email $email): array;
}