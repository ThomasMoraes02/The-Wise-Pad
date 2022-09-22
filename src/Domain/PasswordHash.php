<?php 
namespace App\Domain;

interface PasswordHash
{
    public function getPasswordHash(): string;

    public function encoded(string $passwordText): string;

    public function verify(string $passwordText, string $passwordHash): bool;
}