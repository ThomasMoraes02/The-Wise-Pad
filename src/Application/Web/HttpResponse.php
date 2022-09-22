<?php 
namespace App\Application\Web;

interface HttpResponse
{
    public static function statusCode(int $number);

    public static function body(array $any);
}