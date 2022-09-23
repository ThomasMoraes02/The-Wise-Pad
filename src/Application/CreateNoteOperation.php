<?php 
namespace App\Application;

use App\Domain\UseCases\UseCase;
use App\Application\Web\ControllerOperation;
use App\Application\Web\HttpHelper;
use App\Domain\Exception\EmailException;
use App\Domain\Exception\NoteException;
use App\Domain\Exception\UserRepositoryException;
use Throwable;

class CreateNoteOperation implements ControllerOperation
{
    use HttpHelper;

    private $useCase;
    private $requiredParams = ['title', 'content', 'email'];

    public function __construct(UseCase $useCase)
    {
        $this->useCase = $useCase;
    }

    public function specificOp($request)
    {
        try {
            $response = $this->useCase->perform([
                'title' => $request['title'],
                'content' => $request['content'],
                'email' => $request['email']
            ]);

            return $this->created($response);

        } catch(Throwable $e) {
            return $this->forbidden($e);
        }

        return $this->badRequest($response);
    }
}