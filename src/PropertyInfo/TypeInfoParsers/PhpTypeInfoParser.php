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
 *     This class will extract type information available to PHP from Properties, Getters and Setter parameters and it
 * will parse said information into Type[] objects.
 *
 *     Based on the current PHP version more information will be available (ex.: PHP7 has scalar type hinting and Getter
 * return types which are not available in PHP5).
 *
 * @author Mihai Stancu <stancu.t.mihai@gmail.com>
 */
class PhpTypeInfoParser extends NativeTypeInfoParser
{
    /**
     * @param \ReflectionProperty $property
     *
     * @return string|null
     */
    public function getPropertyType(\ReflectionProperty $property)
    {
        return;
    }

    /**
     * @param \ReflectionProperty $property
     *
     * @return string|null
     */
    public function getGetterReturnType(\ReflectionProperty $property)
    {
        $getter = $this->getGetter($property);

        if (PHP_VERSION >= 7 && null !== $getter && $getter->hasReturnType()) {
            return (string) $getter->getReturnType();
        }
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
            if (PHP_VERSION >= 7) {
                return (string) $param->getType();
            }

            if ($param->isArray()) {
                return 'array';
            }

            if ($param->isCallable()) {
                return 'callable';
            }

            if ($class = $param->getClass()) {
                return $class->getName();
            }
        }
    }

    /**
     * @param string $info
     *
     * @return array|Type[]|null
     */
    public function parse($info)
    {
        $type = new Type();

        if ('double' === $info) {
            $type->setType('float');

            return [$type];
        }

        if ('array' === $info) {
            $type->setType('array');
            $type->setCollection(true);

            return [$type];
        }

        if (class_exists($info)) {
            $type->setType('object');
            $type->setClass($info);
            $type->setCollection(true);

            return [$type];
        }

        if (null !== $info) {
            $type->setType($info);

            return [$type];
        }
    }
}
