<?php 
namespace Tests\Infraestructure\User;

use App\Domain\User\User;
use App\Infraestructure\PasswordArgonII;
use App\Infraestructure\User\UserRepositoryMemory;
use PHPUnit\Framework\TestCase;

class UserRepositoryMemoryTest extends TestCase
{
    public function test_add_user_repository_memory()
    {
        $user = User::create("Thomas", "thomas@gmail.com", new PasswordArgonII("123456"));

        $repository = new UserRepositoryMemory();
        $repository->addUser($user);

        $userFind = $repository->getUserByEmail($user->getEmail());

        $this->assertEquals($user->getName(), $userFind->getName());
    }

    public function test_add_2_users_repository_memory()
    {
        $user1 = User::create("Thomas", "thomas@gmail.com", new PasswordArgonII("123456"));
        $user2 = User::create("Igor", "igor@gmail.com", new PasswordArgonII("987654"));

        $repository = new UserRepositoryMemory();
        $repository->addUser($user1);
        $repository->addUser($user2);

        $allUsers = $repository->getAllUsers();

        $this->assertEquals(2, count($allUsers));
    }
}