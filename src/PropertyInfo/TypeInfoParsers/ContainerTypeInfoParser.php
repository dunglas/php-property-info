<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\TypeInfoParsers;

use PropertyInfo\Type;

/**
 * This trait will will parse strings representing a composite type such as ArrayCollection<EntityObject> and
 * return Type[] objects.
 *
 * Known limitation: when parsing type information we can correctly identify array<int, string> or Vector<stdClass> but
 * we do not (currently) parse recursively in depth so we cannot correctly identify array<int, array<string>>.
 *
 * @author Mihai Stancu <stancu.t.mihai@gmail.com>
 */
trait ContainerTypeInfoParser
{
    /**
     * @param string $info
     *
     * @return array|Type[]
     */
    public function parse($info)
    {
        if ('?' === $info[0]) {
            $info = substr($info, 1);
        }

        if (($type = $this->parseSimpleTypes($info)) || ($type = $this->parseContainerTypes($info))) {
            return [$type];
        }
    }

    /**
     * @param string $info
     *
     * @return Type|null
     */
    public function parseSimpleTypes($info)
    {
        $type = new Type();

        if (isset(static::$types[$info])) {
            $type->setType(static::$types[$info]);
            $type->setCollection('array' === static::$types[$info]);

            return $type;
        }

        if (interface_exists($info, true) || class_exists($info, true)) {
            $class = new \ReflectionClass($info);
            $collection = $class->implementsInterface(static::COLLECTION_INTERFACE);

            $type->setType('object');
            $type->setClass($info);
            $type->setCollection($collection);

            return $type;
        }
    }

    /**
     * @param string $info
     *
     * @return Type|null
     */
    protected function parseContainerTypes($info)
    {
        if (false !== ($pos = strpos($info, '<'))) {
            $container = substr($info, 0, $pos);
            $contents = substr($info, $pos + 1, -1);
            $contents = explode(', ', $contents);

            $innerType = $this->parseSimpleTypes(end($contents));

            if ('array' === $container || interface_exists($container, true) ||  class_exists($container, true)) {
                $outerType = $this->parseSimpleTypes($container);

                if ($outerType->isCollection()) {
                    $outerType->setCollectionType($innerType);
                }

                return $outerType;
            }
        }
    }
}
