<?php 
namespace App\Application\Web;

use Error;
use App\Application\Web\HttpRequest;
use App\Application\Web\ControllerOperation;

class WebController
{
    use HttpHelper;
    private $controllerOp;

    public function __construct(ControllerOperation $controllerOp)
    {
        $this->controllerOp = $controllerOp;
    }

    public function handle(HttpRequest $httpRequest)
    {
        try {
            $missingParams = WebController::getMissingParams($httpRequest, $this->controllerOp['requiredParams']);
            if(!empty($missingParams)) {
                return $this->badRequest($missingParams);
            }
            $this->controllerOp->specificOp($httpRequest);
        } catch(Error $e) {
            return $this->serverError($e);
        }
    }

    public static function getMissingParams(HttpRequest $httpRequest, array $requiredParams)
    {
        $missingParams = [];
        $missingParams = array_diff_key($requiredParams, $httpRequest);
        return $missingParams;
    }
}