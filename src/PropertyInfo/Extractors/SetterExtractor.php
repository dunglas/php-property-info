<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Extractors;

use PropertyInfo\Type;
use PropertyInfo\TypeExtractorInterface;

/**
 * Setter Extractor.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class SetterExtractor implements TypeExtractorInterface
{
    public function extractTypes(\ReflectionProperty $reflectionProperty)
    {
        $setterName = sprintf('set%s', ucfirst($reflectionProperty->getName()));
        $reflectionClass = $reflectionProperty->getDeclaringClass();

        if ($reflectionClass->hasMethod($setterName)) {
            $reflectionMethod = $reflectionClass->getMethod($setterName);
            if (1 !== $reflectionMethod->getNumberOfRequiredParameters()) {
                return;
            }

            foreach ($reflectionMethod->getParameters() as $reflectionParameter) {
                if ($reflectionParameter->isOptional()) {
                    continue;
                }

                $type = new Type();
                if ($reflectionParameter->isArray()) {
                    $type->setCollection(true);
                    $type->setType('array');
                } elseif ($reflectionParameter->isCallable()) {
                    $type->setCollection(false);
                    $type->setType('callable');
                } elseif ($typeHint = $reflectionParameter->getClass()) {
                    $type->setCollection(false);
                    $type->setType('object');
                    $type->setClass($typeHint->getName());
                } else {
                    return;
                }

                return [$type];
            }
        }
    }
}
