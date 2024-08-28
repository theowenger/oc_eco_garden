<?php

namespace App\Tools;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CustomExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof AccessDeniedHttpException) {
            $response = new JsonResponse(
                ['error' => 'Access Denied'],
                Response::HTTP_FORBIDDEN
            );
            $event->setResponse($response);
        }
    }
}