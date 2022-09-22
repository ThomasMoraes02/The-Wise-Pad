<?php 
namespace Tests\Domain\UseCases;

use App\Domain\Email;
use App\Domain\Exception\UserRepositoryException;
use App\Domain\User\User;
use PHPUnit\Framework\TestCase;
use App\Domain\UseCases\CreateNote;
use App\Domain\UseCases\LoadNotes;
use App\Infraestructure\PasswordArgonII;
use App\Infraestructure\Note\NoteRepositoryMemory;
use App\Infraestructure\User\UserRepositoryMemory;
use DomainException;

class LoadNotesTest extends TestCase
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

    public function test_load_notes()
    {
        $loadNotes = new LoadNotes($this->noteRepository);
        
        $userData = [
            'email' => 'thomas@gmail.com'
        ];

        $notes = $loadNotes->perform($userData);
        
        $this->assertEquals('Primeira Nota Criada', $notes[0]->getTitle());
    }

    public function test_load_notes_with_invalid_user()
    {
        $loadNotes = new LoadNotes($this->noteRepository);
        
        $userData = [
            'email' => 'igor@gmail.com'
        ];

        $notes = $loadNotes->perform($userData);
        $this->assertEmpty($notes);
    }
}