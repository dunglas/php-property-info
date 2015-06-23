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
 *     This class will extract type information available to HHVM from Properties, Getters and Setter parameters and it
 * will parse said information into Type[] objects.
 *
 *     HHVM has full type hinting (including scalars) and it is available for properties, Getter return types, and
 * Setter parameter types.
 *
 *     Known limitation: when parsing type information we can correctly identify array<int, string> or Vector<stdClass>
 * but we do not (currently) parse recursively in depth so we cannot correctly identify array<int, array<string>>.
 *
 * @author Mihai Stancu <stancu.t.mihai@gmail.com>
 */
class HhvmTypeInfoParser extends NativeTypeInfoParser
{
    protected static $types = array(
        'HH\\bool' => 'bool',
        'HH\\int' => 'int',
        'HH\\float' => 'float',
        'HH\\double' => 'float',
        'HH\\string' => 'string',
        'callable' => 'callable',
        'array' => 'array',
    );

    /**
     * @param \ReflectionProperty $property
     *
     * @return string|null
     */
    public function getPropertyType(\ReflectionProperty $property)
    {
        return $property->getTypeText() ?: null;
    }

    /**
     * @param \ReflectionProperty $property
     *
     * @return string|null
     */
    public function getGetterReturnType(\ReflectionProperty $property)
    {
        $method = $this->getGetter($property);

        return $method->getReturnTypeText();
    }

    /**
     * @param \ReflectionProperty $property
     *
     * @return string|null
     */
    public function getSetterParamType(\ReflectionProperty $property)
    {
        $param = $this->getSetterParam($property);
        if (null !== $param) {
            return $param->getTypeText();
        }
    }

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
            $type->setCollection('array' === $info);
        } elseif (class_exists($info, true)) {
            $class = new \ReflectionClass($info);
            $collection = $class->implementsInterface('HH\\Collection');

            $type->setType('object');
            $type->setClass($info);
            $type->setCollection($collection);
        } else {
            $type = null;
        }

        return $type;
    }

    /**
     * @param string $info
     *
     * @return Type|null
     */
    protected function parseContainerTypes($info)
    {
        $type = new Type();

        if (false !== ($pos = strpos($info, '<'))) {
            $container = substr($info, 0, $pos);
            $contents = substr($info, $pos+1, -1);
            $contents = explode(', ', $contents);

            if ('array' === $container) {
                $types = $this->parse(end($contents));
                $type = reset($types);
                $type->setCollection(true);

                $type->setCollectionType(new Type());
                $type->getCollectionType()->setType('array');
                $type->getCollectionType()->setCollection(false);
            } elseif (class_exists($container, true)) {
                $types = $this->parse(end($contents));
                $type = reset($types);
                $collectionTypes = $this->parse($container);
                $type->setCollectionType(reset($collectionTypes));

                if ($type->getCollectionType()->isCollection()) {
                    $type->setCollection(true);
                    $type->getCollectionType()->setCollection(false);
                } else {
                    $type->setCollectionType(null);
                }
            }
        } else {
            $type = null;
        }

        return $type;
    }
}
