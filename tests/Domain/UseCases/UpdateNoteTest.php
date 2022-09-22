<?php 
namespace Tests\Domain\UseCases;

use App\Domain\Exception\NoteException;
use App\Domain\Exception\NoteRepositoryException;
use App\Domain\User\User;
use PHPUnit\Framework\TestCase;
use App\Domain\UseCases\CreateNote;
use App\Domain\UseCases\UpdateNote;
use App\Infraestructure\PasswordArgonII;
use App\Infraestructure\Note\NoteRepositoryMemory;
use App\Infraestructure\User\UserRepositoryMemory;

class UpdateNoteTest extends TestCase
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

    public function test_update_note()
    {
        $updateNote = new UpdateNote($this->noteRepository, $this->userRepository);

        $updateNoteData = [
            'id' => 0,
            'title' => 'Esse é o titulo atualizado',
            'content' => 'Esse é o novo conteudo',
            'email' => 'thomas@gmail.com'
        ];

        $noteUpdated = $updateNote->perform($updateNoteData);
        
        $this->assertEquals("Esse é o titulo atualizado", $noteUpdated->getTitle());
    }

    public function test_update_note_with_title_exists()
    {
        $this->expectException(NoteException::class);
        $updateNote = new UpdateNote($this->noteRepository, $this->userRepository);

        $updateNoteData = [
            'id' => 0,
            'title' => 'Primeira Nota Criada',
            'content' => 'Esse é o primeiro conteúdo',
            'email' => 'thomas@gmail.com'
        ];

        $updateNote->perform($updateNoteData);
    }
}