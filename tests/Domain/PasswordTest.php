<?php 
namespace Tests\Domain;

use PHPUnit\Framework\TestCase;
use App\Infraestructure\PasswordArgonII;

class PasswordTest extends TestCase
{
    public function test_password_invalid()
    {
        $password = new PasswordArgonII();
        $passwordHash = $password->encoded("123456");
        $passwordVerify = $password->verify("123", $passwordHash);

        $this->assertFalse($passwordVerify);
    }

    public function test_password_valid()
    {
        $password = new PasswordArgonII();
        $passwordHash = $password->encoded("123456");
        $passwordVerify = $password->verify("123456", $passwordHash);

        $this->assertTrue($passwordVerify);
    }

    public function test_new_create_password()
    {
        $password = new PasswordArgonII("123456");
        $passwordVerify = $password->verify("123456", $password->getPasswordHash());

        $this->assertTrue($passwordVerify);
    }
}