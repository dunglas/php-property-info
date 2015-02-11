<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo;

/**
 * Type.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class Type
{
    /**
     * @var string
     */
    private $type;
    /**
     * @var string|null
     */
    private $class;
    /**
     * @var boolean
     */
    private $collection;
    /**
     * @var Type
     */
    private $collectionType;

    /**
     * Gets type.
     *
     * Can be boolean, integer, float, string, array, object, resource, null or callback.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets types.
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Gets class name.
     *
     * Only applicable if the type is object.
     *
     * @return string|null
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Sets class name.
     *
     * @param string|null $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * Is collection?
     *
     * @return boolean
     */
    public function isCollection()
    {
        return $this->collection;
    }

    /**
     * Sets collection.
     *
     * @param boolean $collection
     */
    public function setCollection($collection)
    {
        $this->collection = $collection;
    }

    /**
     * Gets collection type.
     *
     * Only applicable if is a collection.
     *
     * @return Type
     */
    public function getCollectionType()
    {
        return $this->collectionType;
    }

    /**
     * Sets collection type.
     *
     * @param Type $collectionType
     */
    public function setCollectionType(Type $collectionType)
    {
        $this->collectionType = $collectionType;
    }
}
