<?php 
namespace Tests\Application\Web;

use App\Application\CreateNoteOperation;
use App\Domain\User\User;
use PHPUnit\Framework\TestCase;
use App\Infraestructure\PasswordArgonII;
use App\Infraestructure\Token\TokenUniq;
use App\Domain\Authentication\CustomAuthenticate;
use App\Domain\Email;
use App\Domain\UseCases\CreateNote;
use App\Infraestructure\Note\NoteRepositoryMemory;
use App\Infraestructure\User\UserRepositoryMemory;

class CreateNoteOperationTest extends TestCase
{
    private $userRepository;
    private $noteRepository;
    private $tokenManager;
    private $passwordHash;
    private $authentication;

    protected function setUp()
    {
        $this->noteRepository = new NoteRepositoryMemory();
        $this->userRepository = new UserRepositoryMemory();
        $this->tokenManager = new TokenUniq();
        $this->passwordHash = new PasswordArgonII();

        $user = User::create("Thomas", "thomas@gmail.com", new PasswordArgonII("123456"));
        $this->userRepository->addUser($user);

        $this->authentication = new CustomAuthenticate($this->userRepository, $this->passwordHash, $this->tokenManager);
        parent::setUp();
    }

    public function test_create_note_operation()
    {
        $createNote = new CreateNoteOperation(new CreateNote($this->noteRepository, $this->userRepository));

        $requestNote = [
            'title' => 'Esse é o titulo da nota',
            'content' => 'Esse é o conteudo da nota',
            'email' => 'thomas@gmail.com'
        ];

        $response = $createNote->specificOp($requestNote);

        $this->assertEquals(201, $response['statusCode']);
    }

    public function test_create_note_operation_with_user_invalid()
    {
        $createNote = new CreateNoteOperation(new CreateNote($this->noteRepository, $this->userRepository));

        $requestNote = [
            'title' => 'Esse é o titulo da nota',
            'content' => 'Esse é o conteudo da nota',
            'email' => 'igor@gmail.com'
        ];

        $response = $createNote->specificOp($requestNote);

        $this->assertEquals(403, $response['statusCode']);
    }

    public function test_create_note_equals()
    {
        $note = new CreateNote($this->noteRepository, $this->userRepository);

        $note->perform([
            'email' => 'thomas@gmail.com',
            'title' => 'Primeira Nota Criada',
            'content' => 'Esse é o primeiro conteúdo'
        ]);

        $createNote = new CreateNoteOperation(new CreateNote($this->noteRepository, $this->userRepository));

        $requestNote = [
            'title' => 'Esse é o titulo da nota',
            'content' => 'Esse é o conteudo da nota',
            'email' => 'thomas@gmail.com'
        ];

        $response = $createNote->specificOp($requestNote);

        $notesThom = $this->noteRepository->findAllNotesFrom(new Email("thomas@gmail.com"));

        $this->assertEquals(201, $response['statusCode']);
        $this->assertEquals(2, count($notesThom));
    }
}