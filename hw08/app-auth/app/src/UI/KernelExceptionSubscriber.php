<?php

namespace App\UI;

use Psr\Log\LoggerInterface;
use App\UI\Service\Converter\ExceptionConverter;
use App\UI\Service\Response\ResponseJsonFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;

readonly class KernelExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private KernelInterface     $kernel,
        private ExceptionConverter  $exceptionConverter,
        private ResponseJsonFactory $responseJsonFactory,
        private LoggerInterface     $logger,
    ) {}

    public function handle(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ('prod' === $this->kernel->getEnvironment()) {
            $this->logger->error($exception->getMessage(), $exception->getTrace());
        }

        $response = $this->responseJsonFactory->createFailureResponse(
            Response::HTTP_INTERNAL_SERVER_ERROR,
            $this->exceptionConverter->convert($exception),
        );

        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['handle', 1],
        ];
    }
}
