<?php 
namespace Tests\Application\Web;

use App\Domain\User\User;
use App\Domain\UseCases\SignUp;
use PHPUnit\Framework\TestCase;
use App\Application\SignUpOperation;
use App\Application\Web\HttpRequest;
use App\Infraestructure\PasswordArgonII;
use App\Infraestructure\Token\TokenUniq;
use App\Domain\Authentication\CustomAuthenticate;
use App\Infraestructure\User\UserRepositoryMemory;

class SignUpOperationTest extends TestCase
{
    private $repository;
    private $tokenManager;
    private $passwordHash;
    private $authentication;

    protected function setUp()
    {
        $this->repository = new UserRepositoryMemory();
        $this->tokenManager = new TokenUniq();
        $this->passwordHash = new PasswordArgonII();

        $user = User::create("Thomas", "thomas@gmail.com", new PasswordArgonII("123456"));
        $this->repository->addUser($user);

        $this->authentication = new CustomAuthenticate($this->repository, $this->passwordHash, $this->tokenManager);
        parent::setUp();
    }

    public function test_signup_operation()
    {
        $signUp = new SignUpOperation(new SignUp($this->repository, $this->passwordHash, $this->authentication));

        $requestUserData = [
            'name' => 'Thomas',
            'email' => 'thomas@gmail.com',
            'password' => '123456'
        ];

        $response = $signUp->specificOp($requestUserData);

        $this->assertEquals(201, $response['statusCode']);
    }

    public function test_signup_operation_with_invalid_email()
    {
        $signUp = new SignUpOperation(new SignUp($this->repository, $this->passwordHash, $this->authentication));

        $requestUserData = [
            'name' => 'Thomas',
            'email' => 'thomasgmail.com',
            'password' => '123456'
        ];

        $response = $signUp->specificOp($requestUserData);
        $this->assertEquals(403, $response['statusCode']);
    }
}