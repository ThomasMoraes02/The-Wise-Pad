<?php 
namespace App\Domain\UseCases;

interface UseCase
{
    public function perform(array $data);
}