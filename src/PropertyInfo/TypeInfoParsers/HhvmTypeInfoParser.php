<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\TypeInfoParsers;

use PropertyInfo\Type;
use PropertyInfo\TypeInfoParserInterface;

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
class HhvmTypeInfoParser implements TypeInfoParserInterface
{
    const COLLECTION_INTERFACE = 'HH\\Collection';

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
     * @var NativeTypeInfoParser
     */
    private $nativeTypeInfoParser;

    /**
     * @var ContainerTypeInfoParser
     */
    private $containerTypeInfoParser;

    public function __construct()
    {
        $this->nativeTypeInfoParser = new NativeTypeInfoParser();
        $this->containerTypeInfoParser = new ContainerTypeInfoParser(static::COLLECTION_INTERFACE, static::$types);
    }

    /**
     * @param \ReflectionProperty $property
     *
     * @return string|null
     */
    public function getPropertyType(\ReflectionProperty $property)
    {
        return $property->getTypeText() ? $property->getTypeText() : null;
    }

    /**
     * @param \ReflectionProperty $property
     *
     * @return string|null
     */
    public function getGetterReturnType(\ReflectionProperty $property)
    {
        $getter = $this->nativeTypeInfoParser->getGetter($property);

        if (null !== $getter) {
            return $getter->getReturnTypeText();
        }
    }

    /**
     * @param \ReflectionProperty $property
     *
     * @return string|null
     */
    public function getSetterParamType(\ReflectionProperty $property)
    {
        $param = $this->nativeTypeInfoParser->getSetterParam($property);
        if (null !== $param) {
            return $param->getTypeText();
        }
    }

    /**
     * @param string $info
     *
     * @return array|Type[]|null
     */
    public function parse($info)
    {
        return $this->containerTypeInfoParser->parse($info);
    }
}
