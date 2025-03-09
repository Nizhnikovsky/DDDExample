<?php

namespace App\Shared\Abstractions;

use AutoMapperPlus\AutoMapperInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Service\Attribute\Required;

class RestController extends AbstractController
{
    protected AutoMapperInterface $mapper;

    #[Required]
    public function setMapper(AutoMapperInterface $mapper)
    {
        $this->mapper = $mapper;
    }

    public function response($data): JsonResponse
    {
        return new JsonResponse($data);
    }

    public function success(): JsonResponse
    {
        return new JsonResponse(['Status' => 'Success']);
    }

    public function error(string $error, int $statusCode = 400): JsonResponse
    {
        return new JsonResponse(['Error' => $error], $statusCode);
    }
}
