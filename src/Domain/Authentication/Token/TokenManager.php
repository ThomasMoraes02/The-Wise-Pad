<?php 
namespace App\Domain\Authentication\Token;

interface TokenManager
{
    public function sign(string $id): string;
}