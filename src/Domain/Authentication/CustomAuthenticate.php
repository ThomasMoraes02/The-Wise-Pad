<?php 
namespace App\Domain\Authentication;

use App\Domain\PasswordHash;
use App\Domain\User\UserRepository;
use App\Domain\Authentication\Token\TokenManager;
use DomainException;

class CustomAuthenticate implements AuthenticationService
{
    private $userRepository;
    private $passwordHash;
    private $tokenManager;

    public function __construct(UserRepository $userRepository, PasswordHash $passwordHash, TokenManager $tokenManager)
    {
        $this->userRepository = $userRepository;
        $this->passwordHash = $passwordHash;
        $this->tokenManager = $tokenManager;
    }

    public function auth(array $authenticationParams)
    {
        $user = $this->userRepository->getUserByEmail($authenticationParams['email']);
        
        $userPassword = $this->passwordHash->verify($authenticationParams['password'], $user->getPassword()->getPasswordHash());
        
        if($userPassword == false) {
            throw new DomainException("Invalid password");
        }

        $accessToken = $this->tokenManager->sign($user->getEmail());

        return [
            'accessToken' => $accessToken,
            'email' => $user->getEmail()
        ];
    }
}