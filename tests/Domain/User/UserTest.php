<?php 
namespace Tests\Domain\User;

use App\Domain\Email;
use App\Domain\User\User;
use PHPUnit\Framework\TestCase;
use App\Infraestructure\PasswordArgonII;

class UserTest extends TestCase
{
    public function test_create_user()
    {
        $user = new User("Thomas", new Email("thomas@gmail.com"), new PasswordArgonII("123456"));

        $this->assertEquals("Thomas", $user->getName());
        $this->assertEquals("thomas@gmail.com", $user->getEmail());
    }

    public function test_create_user_static()
    {
        $user = User::create("Thomas", "thomas@gmail.com", new PasswordArgonII("123456"));

        $this->assertEquals("Thomas", $user->getName());
        $this->assertEquals("thomas@gmail.com", $user->getEmail());
    }
}