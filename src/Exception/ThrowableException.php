<?php

declare(strict_types=1);

namespace DddModule\CommandBus\Exception;

use Exception;
use Throwable;

/**
 * Class ThrowableException.
 *
 * @category DddModule\CommandBus\Exception
 */
class ThrowableException extends Exception
{
    /**
     * ThrowableException constructor.
     *
     * @param Throwable $throwable
     */
    public function __construct(Throwable $throwable)
    {
        parent::__construct($throwable->getMessage(), (int) $throwable->getCode(), $throwable);
        $this->file = $throwable->getFile();
        $this->line = $throwable->getLine();
    }
}
