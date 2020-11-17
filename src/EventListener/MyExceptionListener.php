<?php


namespace App\EventListener;


use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class MyExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $event->getThrowable();
        dump("Hey, il y a une erreur!!!");
    }
}