<?php 
namespace Tests\Domain\UseCases;

use App\Domain\Exception\NoteRepositoryException;
use App\Domain\User\User;
use PHPUnit\Framework\TestCase;
use App\Domain\UseCases\CreateNote;
use App\Domain\UseCases\RemoveNote;
use App\Infraestructure\PasswordArgonII;
use App\Infraestructure\Note\NoteRepositoryMemory;
use App\Infraestructure\User\UserRepositoryMemory;

class RemoveNoteTest extends TestCase
{
    private $userRepository;
    private $noteRepository;

    protected function setUp()
    {
        $this->userRepository = new UserRepositoryMemory();
        $this->noteRepository = new NoteRepositoryMemory();

        $user = User::create("Thomas", "thomas@gmail.com", new PasswordArgonII("123456"));
        $this->userRepository->addUser($user);

        $note = new CreateNote($this->noteRepository, $this->userRepository);

        $note->perform([
            'email' => 'thomas@gmail.com',
            'title' => 'Primeira Nota Criada',
            'content' => 'Esse é o primeiro conteúdo'
        ]);
    }

    public function test_remove_note()
    {
        $this->expectException(NoteRepositoryException::class);
        $removeNote = new RemoveNote($this->noteRepository);

        $removeNoteData = [
            'id' => 0
        ];

        $removeNote->perform($removeNoteData);
        $this->noteRepository->getNoteById(0);
    }
}