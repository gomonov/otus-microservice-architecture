<?php

namespace App\UI;

use Psr\Log\LoggerInterface;
use App\UI\Service\Converter\ExceptionConverter;
use App\UI\Service\Response\ResponseJsonFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;

class KernelExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly KernelInterface     $kernel,
        private readonly ExceptionConverter  $exceptionConverter,
        private readonly ResponseJsonFactory $responseJsonFactory,
        private readonly LoggerInterface     $logger,
    ) {}

    public function handle(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ('prod' === $this->kernel->getEnvironment()) {
            $this->logger->error($exception->getMessage(), $exception->getTrace());
        }

        $response = $this->responseJsonFactory->createFailureResponse(
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
