<?php 
namespace App\Domain\UseCases;

use App\Domain\Email;
use App\Domain\Authentication\AuthenticationService;

class SigIn implements UseCase
{
    private $authentication;

    public function __construct(AuthenticationService $authentication)
    {
        $this->authentication = $authentication;
    }

    public function perform(array $userData)
    {
        return $this->authentication->auth([
            'email' => new Email($userData['email']),
            'password' => $userData['password']
        ]);
    }
}