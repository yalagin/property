<?php

namespace CelebrityAgent\Entity;

use ReflectionObject;

/**
 * Abstract entity class containing commonly-used helper methods.
 */
abstract class AbstractEntity
{
    /**
     * Validate a property that must be contained within a set of valid values.
     *
     * @param string $exceptionClass The fully qualified class name of the exception to throw.
     * @param string $property The name of the property to validate.
     * @param array $valid The valid values for the property.
     * @param bool $allowEmpty If set to true, an empty value is considered okay.
     *
     * @throws CelebrityAgentException
     */
    protected function validateMemberOf(string $exceptionClass, string $property, array $valid, bool $allowEmpty = false): void
    {
        $reflectionObject = new ReflectionObject($this);
        $reflectionProperty = $reflectionObject->getProperty($property);
        $reflectionProperty->setAccessible(true);

        if (!in_array($reflectionProperty->getValue($this), $valid) && !($allowEmpty && empty($reflectionProperty->getValue($this)))) {
            throw call_user_func([$exceptionClass, 'invalidArgument'], self::getCaller(), $property, $reflectionProperty->getValue($this), $valid);
        }
    }

    /**
     * Validate non-empty properties.
     *
     * @param string $exceptionClass The fully qualified class name of the exception to throw.
     * @param array<string> $properties An array of property names to validate.
     *
     * @throws CelebrityAgentException
     */
    protected function validateNonEmptyProperties(string $exceptionClass, array $properties): void
    {
        $reflectionObject = new ReflectionObject($this);

        foreach ($properties as $property) {

            $reflectionProperty = $reflectionObject->getProperty($property);
            $reflectionProperty->setAccessible(true);

            if (empty($reflectionProperty->getValue($this))) {
                throw call_user_func([$exceptionClass, 'emptyProperty'], self::getCaller(), $property);
            }
        }
    }
}
