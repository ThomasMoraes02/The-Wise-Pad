<?php 
namespace App\Application;

use App\Domain\UseCases\UseCase;
use App\Application\Web\ControllerOperation;
use App\Application\Web\HttpHelper;
use App\Application\Web\WebController;
use Throwable;

class UpdateNoteOperation implements ControllerOperation
{
    use HttpHelper;

    private $useCase;
    private $requiredParams = ['id', 'email'];

    public function __construct(UseCase $useCase)
    {
        $this->useCase = $useCase;
    }

    public function specificOp($request)
    {
        try {
            $updateParams = ['title', 'content'];
            $missingParams = WebController::getMissingParams($request, $updateParams);

            if(!empty($missingParams)) {
                return $this->badRequest($missingParams);
            }
    
            $response = $this->useCase->perform($request);
            return $this->ok($response);

        } catch(Throwable $e) {
            return $this->badRequest($e->getMessage());
        }
    }
}