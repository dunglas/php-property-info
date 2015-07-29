<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\TypeInfoParsers;

use PropertyInfo\TypeInfoParserInterface;

/**
 *    This abstract supplies a few helper functions for reusable retrieval of reflection data associated with a
 * \ReflectionProperty objects.
 *
 *    For example it will retrieve the \ReflectionMethod of a Getter associated with a particular \ReflectionProperty.
 *
 * @author Mihai Stancu <stancu.t.mihai@gmail.com>
 */
abstract class NativeTypeInfoParser implements TypeInfoParserInterface
{
    const GETTER_FORMAT = 'get%s';
    const SETTER_FORMAT = 'set%s';

    /**
     * @param \ReflectionProperty $property
     *
     * @return \ReflectionMethod|null
     */
    public function getGetter(\ReflectionProperty $property)
    {
        $getter = sprintf(static::GETTER_FORMAT, ucfirst($property->getName()));
        $class = $property->getDeclaringClass();
        if ($class->hasMethod($getter)) {
            return $class->getMethod($getter);
        }
    }

    /**
     * @param \ReflectionProperty $property
     *
     * @return \ReflectionMethod|null
     */
    public function getSetter(\ReflectionProperty $property)
    {
        $setter = sprintf(static::SETTER_FORMAT, ucfirst($property->getName()));
        $class = $property->getDeclaringClass();
        if ($class->hasMethod($setter)) {
            return $class->getMethod($setter);
        }
    }

    /**
     * @param \ReflectionProperty $property
     *
     * @return \ReflectionParameter|null
     */
    public function getSetterParam(\ReflectionProperty $property)
    {
        $setter = $this->getSetter($property);
        if (null === $setter || 1 !== $setter->getNumberOfRequiredParameters()) {
            return;
        }

        $params = $setter->getParameters();
        foreach ($params as $param) {
            if ($param->isOptional()) {
                continue;
            }

            return $param;
        }
    }
}
