<?php 
namespace App\Infraestructure;

use App\Domain\PasswordHash;

class PasswordArgonII implements PasswordHash
{
    private $passwordHash;

    public function __construct(string $passwordText = null)
    {
        if(!is_null($passwordText)) {
            $this->passwordHash = $this->encoded($passwordText);
        }
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function encoded(string $passwordText): string
    {
        return password_hash($passwordText, PASSWORD_ARGON2I);
    }

    public function verify(string $passwordText, string $passwordHash): bool
    {
        return password_verify($passwordText, $passwordHash);
    }
}