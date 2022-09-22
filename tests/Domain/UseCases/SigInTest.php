<?php 
namespace Tests\Domain\UseCases;

use App\Domain\User\User;
use PHPUnit\Framework\TestCase;
use App\Infraestructure\PasswordArgonII;
use App\Infraestructure\Token\TokenUniq;
use App\Domain\Authentication\CustomAuthenticate;
use App\Domain\UseCases\SigIn;
use App\Infraestructure\User\UserRepositoryMemory;

class SigInTest extends TestCase
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

    public function test_user_logout()
    {
        $userData = [
            'name' => 'Thomas',
            'email' => 'thomas@gmail.com',
            'password' => '123456'
        ];

        $auth = new SigIn($this->authentication);
        $response = $auth->perform($userData);

        $this->assertEquals("thomas@gmail.com", $response['email']);
    }
}