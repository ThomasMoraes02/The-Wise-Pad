<?php 
namespace App\Domain\User;

use App\Domain\Email;
use App\Domain\User\User;

interface UserRepository
{
    public function addUser(User $user): void;

    public function getUserByEmail(Email $email): User;

    public function getAllUsers(): array;
}