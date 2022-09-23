<?php 
namespace App\Application\Web;

Trait HttpHelper
{
    public function ok($data)
    {
        return [
            'statusCode' => 200,
            'body' => $data
        ];
    }

    public function created($data)
    {
        return [
            'statusCode' => 201,
            'body' => $data
        ];
    }

    public function forbidden($error)
    {
        return [
            'statusCode' => 403,
            'body' => $error
        ];
    }

    public function badRequest($error)
    {
        return [
            'statusCode' => 400,
            'body' => $error
        ];
    }

    public function serverError($error)
    {
        return [
            'statusCode' => 500,
            'body' => $error
        ];
    }
}