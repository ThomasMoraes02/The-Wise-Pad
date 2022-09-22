<?php
namespace Tests\Domain\UseCases;

use App\Domain\Authentication\CustomAuthenticate;
use App\Domain\UseCases\SignUp;
use App\Domain\User\User;
use App\Infraestructure\PasswordArgonII;
use App\Infraestructure\Token\TokenUniq;
use App\Infraestructure\User\UserRepositoryMemory;
use DomainException;
use PHPUnit\Framework\TestCase;

class SignUpTest extends TestCase
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

    public function test_user_login()
    {
        $userData = [
            'name' => 'Thomas',
            'email' => 'thomas@gmail.com',
            'password' => '123456'
        ];

        $auth = new SignUp($this->repository, $this->passwordHash, $this->authentication);
        $response = $auth->perform($userData);

        $this->assertEquals("thomas@gmail.com", $response['email']);
    }

    public function test_user_invalid_password()
    {
        $this->expectExceptionMessage("Invalid password");

        $userData = [
            'name' => 'Thomas',
            'email' => 'thomas@gmail.com',
            'password' => '987654'
        ];

        $auth = new SignUp($this->repository, $this->passwordHash, $this->authentication);
        $auth->perform($userData);
    }

    public function test_new_user_sigin()
    {
        $userData = [
            'name' => 'Igor',
            'email' => 'igor@gmail.com',
            'password' => '123456'
        ];

        $auth = new SignUp($this->repository, $this->passwordHash, $this->authentication);
        $response = $auth->perform($userData);

        $this->assertEquals("igor@gmail.com", $response['email']);
        $this->assertEquals(2, count($this->repository->getAllUsers()));
    }
}