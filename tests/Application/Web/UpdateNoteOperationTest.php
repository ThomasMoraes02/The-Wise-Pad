<?php 
namespace Tests\Application\Web;

use App\Application\UpdateNoteOperation;
use App\Domain\User\User;
use PHPUnit\Framework\TestCase;
use App\Domain\UseCases\CreateNote;
use App\Infraestructure\PasswordArgonII;
use App\Infraestructure\Token\TokenUniq;
use App\Domain\Authentication\CustomAuthenticate;
use App\Domain\UseCases\UpdateNote;
use App\Infraestructure\Note\NoteRepositoryMemory;
use App\Infraestructure\User\UserRepositoryMemory;

class UpdateNoteOperationTest extends TestCase
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

        $note = new CreateNote($this->noteRepository, $this->userRepository);

        $note->perform([
            'email' => 'thomas@gmail.com',
            'title' => 'Primeira Nota Criada',
            'content' => 'Esse é o primeiro conteúdo'
        ]);

        $this->authentication = new CustomAuthenticate($this->userRepository, $this->passwordHash, $this->tokenManager);
        parent::setUp();
    }

    public function test_update_note_operation()
    {
        $updateNote = new UpdateNoteOperation(new UpdateNote($this->noteRepository, $this->userRepository));

        $requestUpdateNote = [
            'title' => 'Esse é o novo título',
            'content' => 'conteudo novo por aqui',
            'email' => 'thomas@gmail.com',
            'id' => 0
        ];

        $response = $updateNote->specificOp($requestUpdateNote);
        $this->assertEquals(200, $response['statusCode']);
    }

    public function test_update_note_operation_with_invalid_field()
    {
        $updateNote = new UpdateNoteOperation(new UpdateNote($this->noteRepository, $this->userRepository));

        $requestUpdateNote = [
            'titulo' => 'Esse é o novo título',
            'conteudo' => 'conteudo novo por aqui',
            'email' => 'thomas@gmail.com',
            'id' => 0
        ];

        $response = $updateNote->specificOp($requestUpdateNote);
        $this->assertEquals(400, $response['statusCode']);
    }
}