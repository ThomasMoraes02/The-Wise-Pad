<?php 
namespace App\Domain\UseCases;

use App\Domain\PasswordHash;
use App\Domain\User\UserRepository;
use App\Domain\Authentication\AuthenticationService;
use App\Domain\Email;
use App\Domain\Exception\UserRepositoryException;
use App\Domain\User\User;
use DomainException;

class SignUp implements UseCase
{
    private $userRepository;
    private $passwordHash;
    private $authentication;

    public function __construct(UserRepository $userRepository, PasswordHash $passwordHash, AuthenticationService $authentication)
    {
        $this->userRepository = $userRepository;
        $this->passwordHash = $passwordHash;
        $this->authentication = $authentication;
    }

    public function perform($userData)
    {
        try {
            $userFind = $this->userRepository->getUserByEmail(new Email($userData['email']));
            $userPasswordHash = $this->passwordHash->verify($userData['password'], $userFind->getPassword()->getPasswordHash());
    
            if($userPasswordHash === false) {
                throw new DomainException("Invalid password");
            }
        } catch(UserRepositoryException $e) {
            $userFind = User::create($userData['name'], $userData['email'], new $this->passwordHash($userData['password']));
            $this->userRepository->addUser($userFind);
        }

        $response = $this->authentication->auth([
            'name' => $userFind->getName(),
            'email' => $userFind->getEmail(),
            'password' => $userData['password'],
        ]);

        return $response;
    }
}