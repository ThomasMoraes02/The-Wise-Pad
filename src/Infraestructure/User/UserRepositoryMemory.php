<?php 
namespace App\Infraestructure\User;

use App\Domain\Email;
use App\Domain\Exception\UserRepositoryException;
use App\Domain\User\User;
use App\Domain\User\UserRepository;
use DomainException;

class UserRepositoryMemory implements UserRepository
{
    private $users = [];

    public function addUser(User $user): void
    {
        $this->users[] = $user;
    }

    public function getUserByEmail(Email $email): User
    {
        $userFind = array_filter($this->users, function($user) use ($email) {
            if($user->getEmail() == $email) {
                return $user;
            }
        });

        if(empty($userFind)) {
            throw new UserRepositoryException($email);
        }

        return current($userFind);
    }

    public function getAllUsers(): array
    {
        return $this->users;
    }
}