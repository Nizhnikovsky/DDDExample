<?php

namespace App\Infrastructure\Listeners;

use App\Shared\Exception\ApiExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

#[AsEventListener(event: ExceptionEvent::class)]
class ExceptionListener
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $statusCode = 500;
        $responseData = ['success' => false];

        if ($exception instanceof ValidationFailedException) {
            $statusCode = $exception->getCode();
            $responseData['error'] = 'Validation failed';
            $responseData['validation_errors'] = $this->formatValidationErrors($exception->getViolations());
        }

        if ($exception instanceof UnprocessableEntityHttpException) {
            $statusCode = $exception->getStatusCode();
            $responseData['error'] = 'Validation failed';
            $responseData['validation_errors'] = $this->formatValidationErrors($exception->getPrevious()->getViolations());
        } elseif ($exception instanceof HttpExceptionInterface || $exception instanceof ApiExceptionInterface) {
            $statusCode = $exception->getStatusCode();
            $responseData['error'] = $exception->getMessage();
        } else {
            $responseData['error'] = 'An unexpected error occurred';
        }

        $this->logger->error(sprintf('%s: %s', $responseData['error'], $exception->getMessage()), [
            'exception' => $exception,
        ]);

        $event->setResponse(new JsonResponse($responseData, $statusCode));
    }

    private function formatValidationErrors(ConstraintViolationListInterface $violations): array
    {
        $errors = [];
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }
}
