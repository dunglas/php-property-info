<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Extractors;

use PropertyInfo\PropertyTypeInfoInterface;
use PropertyInfo\Type;

/**
 * Extracts type info using reflection.
 *
 * Supports PHP (including PHP 7) and Hack.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 * @author Mihai Stancu <stancu.t.mihai@gmail.com>
 */
class ReflectionExtractor implements PropertyTypeInfoInterface
{
    /**
     * @var string[]
     */
    public static $mutatorPrefixes = array('add', 'remove', 'set');
    /**
     * @var string[]
     */
    public static $accessorPrefixes = array('is', 'can', 'get');
    /**
     * @var array[]
     */
    public static $arrayMutatorPrefixes = array('add', 'remove');

    /**
     * {@inheritdoc}
     */
    public function getTypes($class, $property)
    {
        if (defined('HHVM_VERSION') && $fromProperty = $this->extractFromProperty($class, $property)) {
            return $fromProperty;
        }

        $ucProperty = ucfirst($property);

        if ($fromMutator = $this->extractFromMutator($class, $ucProperty)) {
            return $fromMutator;
        }

        if ($fromAccessor = $this->extractFromAccessor($class, $ucProperty)) {
            return $fromAccessor;
        }
    }

    private function extractFromProperty($class, $property)
    {
        try {
            $reflectionProperty = new \ReflectionProperty($class, $property);
        } catch (\ReflectionException $reflectionException) {
            return;
        }

        if ($typeText = $reflectionProperty->getTypeText()) {
            return $this->parseHackType($typeText);
        }
    }

    /**
     * Tries to extract type information from mutators.
     *
     * @param string $class
     * @param string $ucProperty
     *
     * @return Type[]|null
     */
    private function extractFromMutator($class, $ucProperty)
    {
        foreach (self::$mutatorPrefixes as $prefix) {
            try {
                $reflectionMethod = new \ReflectionMethod($class, $prefix.$ucProperty);

                // Parameter can be optional to allow things like: method(array $foo = null)
                if ($reflectionMethod->getNumberOfParameters() >= 1) {
                    break;
                }
            } catch (\ReflectionException $reflectionException) {
                // Try the next prefix if the method doesn't exist
            }
        }

        if (!isset($reflectionMethod)) {
            return;
        }

        $reflectionParameters = $reflectionMethod->getParameters();
        $reflectionParameter = $reflectionParameters[0];

        if ($reflectionParameter->isArray()) {
            $phpType = Type::BUILTIN_TYPE_ARRAY;
            $collection = true;
        }

        if (in_array($prefix, self::$arrayMutatorPrefixes)) {
            $collection = true;
            $nullable = false;
            $collectionNullable = $reflectionParameter->allowsNull();
        } else {
            $nullable = $reflectionParameter->allowsNull();
            $collectionNullable = false;
        }

        if (!isset($collection)) {
            $collection = false;
        }

        if (method_exists($reflectionParameter, 'isCallable') && $reflectionParameter->isCallable()) {
            $phpType = Type::BUILTIN_TYPE_CALLABLE;
        }

        if ($typeHint = $reflectionParameter->getClass()) {
            if ($collection) {
                $phpType = Type::BUILTIN_TYPE_ARRAY;

                $collectionKeyType = new Type(Type::BUILTIN_TYPE_INT);
                $collectionValueType = new Type(Type::BUILTIN_TYPE_OBJECT, $collectionNullable, $typeHint->name);
            } else {
                $phpType = Type::BUILTIN_TYPE_OBJECT;
                $typeClass = $typeHint->name;
            }
        }

        // Nothing useful extracted
        if (!isset($phpType)) {
            return;
        }

        return array(
            new Type(
                $phpType,
                $nullable,
                isset($typeClass) ? $typeClass : null,
                $collection,
                isset($collectionKeyType) ? $collectionKeyType : null,
                isset($collectionValueType) ? $collectionValueType : null
            ),
        );
    }

    /**
     * Tries to extract type information from accessors.
     *
     * @param string $class
     * @param string $ucProperty
     *
     * @return Type[]|null
     */
    private function extractFromAccessor($class, $ucProperty)
    {
        foreach (self::$accessorPrefixes as $prefix) {
            try {
                $reflectionMethod = new \ReflectionMethod($class, $prefix.$ucProperty);

                if (0 === $reflectionMethod->getNumberOfRequiredParameters()) {
                    break;
                }
            } catch (\ReflectionException $reflectionException) {
                // Try the next prefix if the method doesn't exist
            }
        }

        if (!isset($reflectionMethod)) {
            return;
        }

        if (method_exists($reflectionMethod, 'getReturnType') && $reflectionType = $reflectionMethod->getReturnType()) {
            return $this->extractFromReturnType($reflectionType);
        }

        if (in_array($prefix, array('is', 'can'))) {
            return array(new Type(Type::BUILTIN_TYPE_BOOL));
        }
    }

    /**
     * Extracts from PHP 7 return type.
     *
     * @param \ReflectionType $reflectionType
     *
     * @return Type[]
     */
    private function extractFromReturnType(\ReflectionType $reflectionType)
    {
        $phpTypeOrClass = (string) $reflectionType;
        $nullable = $reflectionType->allowsNull();

        if ($reflectionType->isBuiltin()) {
            if (Type::BUILTIN_TYPE_ARRAY === $phpTypeOrClass) {
                $type = new Type(Type::BUILTIN_TYPE_ARRAY, $nullable, null, true);
            } else {
                $type = new Type($phpTypeOrClass, $nullable);
            }
        } else {
            $type = new Type(Type::BUILTIN_TYPE_OBJECT, $nullable, $phpTypeOrClass);
        }

        return array($type);
    }

    private function parseHackType()
    {
    }
}
