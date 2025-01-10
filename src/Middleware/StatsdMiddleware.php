<?php

declare(strict_types=1);

namespace DddModule\CommandBus\Middleware;

use League\Tactician\Middleware;
use M6Web\Component\Statsd\Client;
use ReflectionClass;
use ReflectionException;
use Throwable;

/**
 * Add support for sending statistic to statsd client.
 *
 * @category Middleware
 */
class StatsdMiddleware implements Middleware
{
    /**
     * @var Client
     */
    private $statsdClient;

    /**
     * Event name.
     *
     * @var string
     */
    private $statEventName;

    /**
     * StatsdMiddleware constructor.
     *
     * @param Client $statsdClient
     * @param string $statEventName
     */
    public function __construct(Client $statsdClient, string $statEventName)
    {
        $this->statsdClient = $statsdClient;
        $this->statEventName = $statEventName;
    }

    /**
     * Execute command object.
     *
     * @param object   $command
     * @param callable $next
     *
     * @return mixed
     *
     * @throws ReflectionException
     * @throws Throwable
     */
    public function execute($command, callable $next)
    {
        $commandName = (new ReflectionClass($command))->getShortName();
        $commandName = strtolower($commandName);
        $this->statsdClient->increment($this->statEventName.'.'.$commandName.'.recieved');

        try {
            $returnValue = $next($command);
            $this->statsdClient->increment($this->statEventName.'.'.$commandName.'.succeeded');

            return $returnValue;
        } catch (Throwable $e) {
            $this->statsdClient->increment($this->statEventName.'.'.$commandName.'.failed');

            throw $e;
        }
    }
}
