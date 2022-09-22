<?php 
namespace App\Application\Web;

interface HttpRequest
{
    public function body(array $body);
}