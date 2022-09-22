<?php 
namespace App\Application\Web;

Trait HttpHelper
{
    public function ok($data)
    {
        return [
            'statusCode' => HttpResponse::statusCode(200),
            'body' => HttpResponse::body($data)
        ];
    }

    public function created($data)
    {
        return [
            'statusCode' => HttpResponse::statusCode(201),
            'body' => HttpResponse::body($data)
        ];
    }

    public function forbidden($error)
    {
        return [
            'statusCode' => HttpResponse::statusCode(403),
            'body' => HttpResponse::body($error)
        ];
    }

    public function badRequest($error)
    {
        return [
            'statusCode' => HttpResponse::statusCode(400),
            'body' => HttpResponse::body($error)
        ];
    }

    public function serverError($error)
    {
        return [
            'statusCode' => HttpResponse::statusCode(500),
            'body' => HttpResponse::body($error)
        ];
    }
}