<?php

namespace App\Controller;

use App\Constraints\RequestConstraints;
use App\Service\RequestParser;
use App\Service\ResponseHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function handleRequest(Request $request): JsonResponse
    {
        $constraints = RequestConstraints::getRequestConstraints();
        $params = $this->parser->normalizeInput($request);
        $violations = $this->validator->validate($params, $constraints);


        if (count($violations) > 0) {
            return new JsonResponse($this->handleValidationErrors($violations));
        } else {
            $data = $this->responseHandler->resolve($params, $request);
            $response = new JsonResponse($data);
            $response->setEncodingOptions(JSON_PRETTY_PRINT); // Set the JSON_PRETTY_PRINT option

            return $response;
        }
    }
}
