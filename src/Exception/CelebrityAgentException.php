<?php

namespace CelebrityAgent\Exception;

use RuntimeException;

/**
 * An exception class to contain all other custom exceptions.
 */
class CelebrityAgentException extends RuntimeException
{
    /**
     * Throw an exception indicating that a property was empty.
     *
     * @param string $method The name of the method being invoked.
     * @param string $property The property that was empty.
     *
     * @return CelebrityAgentException
     */
    public static function emptyProperty(string $method, string $property): CelebrityAgentException
    {
        $class = static::class;

        return new $class(sprintf('The argument "%s" passed to %s must not be empty.', $property, $method));
    }

    /**
     * Throw an exception indicating that a method was invoked with an invalid
     * argument value, which must come from the supplied choice or choices.
     *
     * @param string $method The name of the method being invoked.
     * @param string $argument The name of the argument that was invalid.
     * @param string $received The invalid value that was received.
     * @param array<string> $expected The values that are valid.
     *
     * @return CelebrityAgentException
     */
    public static function invalidArgument(string $method, string $argument, string $received, array $expected): CelebrityAgentException
    {
        $class = static::class;

        return new $class(sprintf(
            'The %s "%s" passed to %s is not valid; %s.',
            $argument,
            $received,
            $method,
            count($expected) == 1 ?
                sprintf('it must be "%s"', array_values($expected)[0]) :
                sprintf('valid choices are: %s', implode(', ', $expected))
        ));
    }
}
