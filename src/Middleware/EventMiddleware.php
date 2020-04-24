<?php

declare(strict_types=1);

namespace MicroModule\CommandBus\Middleware;

use MicroModule\CommandBus\Event\CommandFailed;
use League\Tactician\CommandEvents\Event\CommandHandled;
use League\Tactician\CommandEvents\Event\CommandReceived;
use Throwable;

/**
 * Provides an event-driven middleware functionality.
 */
class EventMiddleware extends \League\Tactician\CommandEvents\EventMiddleware
{
    /**
     * {@inheritdoc}
     */
    public function execute($command, callable $next)
    {
        try {
            $this->getEmitter()->emit(new CommandReceived($command));
            $returnValue = $next($command);
            $this->getEmitter()->emit(new CommandHandled($command));

            return $returnValue;
        } catch (Throwable $e) {
            $this->getEmitter()->emit($event = new CommandFailed($command, $e));

            if (!$event->isExceptionCaught()) {
                throw $e;
            }
        }
    }
}
