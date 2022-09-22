<?php 
namespace Tests\Domain;

use App\Domain\Email;
use DomainException;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function test_create_email_invalid()
    {
        $this->expectException(DomainException::class);
        new Email("e-mail invalid");
    }

    public function test_create_email_valid()
    {
        $email = new Email("thomas@gmail.com");
        $this->assertEquals("thomas@gmail.com", $email);
    }
}