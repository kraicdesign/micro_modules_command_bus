<?php

declare(strict_types=1);

namespace MicroModule\CommandBus\Event;

use Throwable;

/**
 * Emitted when a command is failed.
 */
class CommandFailed extends \League\Tactician\CommandEvents\Event\CommandFailed
{
    public const EVENT_FAILED_COMMAND = 'command.failed';

    /**
     * @var Throwable
     */
    protected Throwable $exception;

    /**
     * @param object    $command
     * @param Throwable $exception
     */
    public function __construct($command, Throwable $exception)
    {
        $this->command = $command;
        $this->exception = $exception;

        $this->name = self::EVENT_FAILED_COMMAND;
    }

    /**
     * Returns the exception.
     *
     * @return Throwable
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function getException(): Throwable
    {
        return $this->exception;
    }
}
