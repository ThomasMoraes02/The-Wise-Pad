<?php 
namespace App\Application\Web;

interface ControllerOperation
{
    public function specificOp(HttpRequest $request);
}