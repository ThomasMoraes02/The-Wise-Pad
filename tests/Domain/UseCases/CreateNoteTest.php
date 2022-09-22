<?php 
namespace Tests\Domain\UseCases;

use App\Domain\Email;
use App\Domain\Exception\NoteException;
use App\Domain\UseCases\CreateNote;
use App\Domain\User\User;
use App\Infraestructure\Note\NoteRepositoryMemory;
use App\Infraestructure\PasswordArgonII;
use App\Infraestructure\User\UserRepositoryMemory;
use PHPUnit\Framework\TestCase;

class CreateNoteTest extends TestCase
{
    private $userRepository;
    private $noteRepository;

    protected function setUp()
    {
        $this->userRepository = new UserRepositoryMemory();
        $this->noteRepository = new NoteRepositoryMemory();

        $user = User::create("Thomas", "thomas@gmail.com", new PasswordArgonII("123456"));
        $this->userRepository->addUser($user);
    }

    public function test_create_note()
    {
        $note = new CreateNote($this->noteRepository, $this->userRepository);

        $note->perform([
            'email' => 'thomas@gmail.com',
            'title' => 'Primeira Nota Criada',
            'content' => 'Esse é o primeiro conteúdo'
        ]);

        $thom_notes = $this->noteRepository->findAllNotesFrom(new Email("thomas@gmail.com"));
        
        $this->assertEquals(1, count($thom_notes));
    }

    public function test_create_two_equal_notes()
    {
        $this->expectException(NoteException::class);

        $note = new CreateNote($this->noteRepository, $this->userRepository);

        $note->perform([
            'email' => 'thomas@gmail.com',
            'title' => 'Primeira Nota Criada',
            'content' => 'Esse é o primeiro conteúdo'
        ]);

        $note2 = new CreateNote($this->noteRepository, $this->userRepository);

        $note2->perform([
            'email' => 'thomas@gmail.com',
            'title' => 'Primeira Nota Criada',
            'content' => 'Esse é o primeiro conteúdo'
        ]);
    }

    public function test_create_two_notes()
    {
        $note = new CreateNote($this->noteRepository, $this->userRepository);

        $note->perform([
            'email' => 'thomas@gmail.com',
            'title' => 'Primeira Nota Criada',
            'content' => 'Esse é o primeiro conteúdo'
        ]);

        $note2 = new CreateNote($this->noteRepository, $this->userRepository);

        $note2->perform([
            'email' => 'thomas@gmail.com',
            'title' => 'Segunda Nota Criada',
            'content' => 'Esse é o segundo conteúdo'
        ]);

        $thom_notes = $this->noteRepository->findAllNotesFrom(new Email("thomas@gmail.com"));
        
        $this->assertEquals(2, count($thom_notes));
    }
}