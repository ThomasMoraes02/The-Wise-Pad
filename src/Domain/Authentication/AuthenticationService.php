<?php 
namespace App\Domain\Authentication;

interface AuthenticationService
{
    public function auth(array $authenticationParams);
}