<?php 
namespace App\Infraestructure\Token;

use App\Domain\Authentication\Token\TokenManager;

class TokenUniq implements TokenManager
{
    public function sign(string $id): string
    {
        return uniqid("auth-" . md5(rand()), true);
    }
}