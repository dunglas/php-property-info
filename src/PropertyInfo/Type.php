<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo;

/**
 * Type value object (immutable).
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class Type
{
    const BUILTIN_TYPE_INT = 'int';
    const BUILTIN_TYPE_FLOAT = 'float';
    const BUILTIN_TYPE_STRING = 'string';
    const BUILTIN_TYPE_BOOL = 'bool';
    const BUILTIN_TYPE_RESOURCE = 'resource';
    const BUILTIN_TYPE_OBJECT = 'object';
    const BUILTIN_TYPE_ARRAY = 'array';
    const BUILTIN_TYPE_NULL = 'null';
    const BUILTIN_TYPE_CALLABLE = 'callable';

    /**
     * List of PHP builtin types.
     *
     * @var string[]
     */
    public static $builtinTypes = [
        self::BUILTIN_TYPE_INT,
        self::BUILTIN_TYPE_FLOAT,
        self::BUILTIN_TYPE_STRING,
        self::BUILTIN_TYPE_BOOL,
        self::BUILTIN_TYPE_RESOURCE,
        self::BUILTIN_TYPE_OBJECT,
        self::BUILTIN_TYPE_ARRAY,
        self::BUILTIN_TYPE_CALLABLE,
        self::BUILTIN_TYPE_NULL,
    ];

    /**
     * @var string
     */
    private $builtinType;
    /**
     * @var bool
     */
    private $nullable;
    /**
     * @var string|null
     */
    private $class;
    /**
     * @var bool
     */
    private $collection;
    /**
     * @var Type|null
     */
    private $collectionKeyType;
    /**
     * @var Type|null
     */
    private $collectionValueType;

    /**
     * @param string      $builtinType
     * @param bool        $nullable
     * @param string|null $class
     * @param bool        $collection
     * @param Type|null   $collectionKeyType
     * @param Type|null   $collectionValueType
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($builtinType, $nullable = false, $class = null, $collection = false, Type $collectionKeyType = null, Type $collectionValueType = null)
    {
        if (!in_array($builtinType, self::$builtinTypes)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a PHP valid type.', $builtinType));
        }

        $this->builtinType = $builtinType;
        $this->nullable = $nullable;
        $this->class = $class;
        $this->collection = $collection;
        $this->collectionKeyType = $collectionKeyType;
        $this->collectionValueType = $collectionValueType;
    }

    /**
     * Gets built-in type.
     *
     * Can be bool, int, float, string, array, object, resource, null or callback.
     *
     * @return string
     */
    public function getBuiltinType()
    {
        return $this->builtinType;
    }

    /**
     * Allows null value?
     *
     * @return bool
     */
    public function isNullable()
    {
        return $this->nullable;
    }

    /**
     * Gets the class name.
     *
     * Only applicable if the built-in type is object.
     *
     * @return string|null
     */
    public function getClassName()
    {
        return $this->class;
    }
    /**
     * Is collection?
     *
     * @return bool
     */
    public function isCollection()
    {
        return $this->collection;
    }

    /**
     * Gets collection key type.
     *
     * Only applicable for a collection type.
     *
     * @return Type|null
     */
    public function getCollectionKeyType()
    {
        return $this->collectionKeyType;
    }

    /**
     * Gets collection value type.
     *
     * Only applicable for a collection type.
     *
     * @return Type|null
     */
    public function getCollectionValueType()
    {
        return $this->collectionValueType;
    }
}
