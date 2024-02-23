<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class PageNotFoundSubscriber implements EventSubscriberInterface
{
    public function __construct(private RequestStack $requestStack)
    {    
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        
        if ($exception instanceof NotFoundHttpException) {
            // display flash message
            $session = $this->requestStack->getSession();
            $session->getFlashBag()->add('danger', 'Page not found 😩');

            // make a redirection
            $event->setResponse(new RedirectResponse('/'));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
