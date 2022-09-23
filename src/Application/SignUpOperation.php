<?php 
namespace App\Application;

use App\Domain\UseCases\UseCase;
use App\Application\Web\HttpHelper;
use App\Application\Web\ControllerOperation;
use App\Domain\Exception\EmailException;
use App\Domain\Exception\UserRepositoryException;

class SignUpOperation implements ControllerOperation
{
    use HttpHelper;

    private $requiredParams = ['name', 'email', 'password'];
    private $useCase;

    public function __construct(UseCase $useCase)
    {
        $this->useCase = $useCase;
    }

    public function specificOp($request)
    {
        try {
            $response = $this->useCase->perform([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => $request['password'],
            ]);

            if($response['email'] == $request['email']) {
                return $this->created($response);
            }    

        } catch(UserRepositoryException | EmailException $e) {
            return $this->forbidden($e);
        }

        return $this->badRequest($response);
    }
}