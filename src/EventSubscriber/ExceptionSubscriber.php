<?php

namespace App\EventSubscriber;

use InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * This class intercepts exceptions thrown by the API and returns a consistent response
*/
readonly class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'handleException',
        ];
    }

    /**
     * @param ExceptionEvent $event
     * Handles exceptions thrown by the API
    */
    public function handleException(ExceptionEvent $event): void
    {

        // [Validator] If contains violations from the validator
        if ($event->getThrowable() instanceof HttpException &&
            !is_null($event->getThrowable()->getPrevious()) &&
            method_exists($event->getThrowable()->getPrevious(), 'getViolations')) {
            $this->handleHttpException($event);
        }

        // [Bad Request errors] Launched, for example, with JWT authentication
        if ($event->getThrowable() instanceof BadRequestHttpException) {
            $this->handleBadRequestException($event);
        }

        // [General Service errors] Launched by services when something is wrong
        if ($event->getThrowable() instanceof InvalidArgumentException) {
            if (str_contains($event->getRequest()->getPathInfo(), '/api/') || $event->getRequest()->isMethod('POST')) {
                $this->handleInvalidArgumentException($event);
            }
        }

        // [404 in APIs] Launched by anything not found BUT ONLY in /api
        if ($event->getThrowable() instanceof NotFoundHttpException) {
            if (str_contains($event->getRequest()->getPathInfo(), '/api/')) {
                $this->handleNotFoundException($event);
            }
        }
    }

    /**
     * @param ExceptionEvent $event
     * Handles Validation errors, intercept the exception thrown by API and return a consistent response
     */
    private function handleHttpException(ExceptionEvent $event): void
    {
        $violations = [];
        $violation_fields = [];
        if (!is_null($event->getThrowable()->getPrevious())) {
            $obj = $event->getThrowable()->getPrevious();
            if (method_exists($obj, 'getViolations')) {
                foreach ($obj->getViolations() as $violation) {
                    if (!in_array($violation->getPropertyPath(), $violation_fields)) {
                        $violation_fields[] = $violation->getPropertyPath();
                        $violations[] = [
                            "property_name" => $violation->getPropertyPath(),
                            "message" => $violation->getMessage(),
                        ];
                    }
                }
            }

        }
        $response = new JsonResponse([
            "message" => $event->getThrowable()->getMessage(),
            "violations" => $violations,
        ], Response::HTTP_BAD_REQUEST);
        $event->setResponse($response);
    }

    private function handleBadRequestException(ExceptionEvent $event): void
    {
        $response = new JsonResponse([
            "message" => $event->getThrowable()->getMessage(),
        ], Response::HTTP_BAD_REQUEST);
        $event->setResponse($response);
    }

    private function handleInvalidArgumentException(ExceptionEvent $event): void
    {
        $response = new JsonResponse([
            "message" => $event->getThrowable()->getMessage(),
        ], Response::HTTP_BAD_REQUEST);
        $event->setResponse($response);
    }

    private function handleNotFoundException(ExceptionEvent $event): void
    {
        $response = new JsonResponse([
            "message" => $event->getThrowable()->getMessage(),
        ], Response::HTTP_NOT_FOUND);
        $event->setResponse($response);
    }

}
