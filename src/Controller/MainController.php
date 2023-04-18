<?php

namespace App\Controller;

use App\Constraints\RequestConstraints;
use App\Service\RequestParser;
use App\Service\ResponseHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MainController extends BaseController
{
    public function __construct(
        private RequestParser $parser,
        private ValidatorInterface $validator,
        private ResponseHandler $responseHandler
    ) {
    }

    #[Route('/api', name: 'handleRequest')]
    public function handleRequest(Request $request)
    {
        $constraints = RequestConstraints::getRequestConstraints();
        $params = $this->parser->normalizeInput($request);
        $violations = $this->validator->validate($params, $constraints);

        if (count($violations) > 0) {
            return $this->handleValidationErrors($violations);
        } else {
            return $this->responseHandler->resolve($params);
        }
    }
}
