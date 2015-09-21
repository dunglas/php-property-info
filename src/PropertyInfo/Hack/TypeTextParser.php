<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Hack;

use PropertyInfo\Type;

/**
 * Builds a {@see Type} from a Hack type text.
 *
 * The Hack language has full type hinting (including scalars) and it is available for properties, getter return
 * types, and setter parameter types.
 *
 * Known limitation: when parsing type information we can correctly identify array<int, string> or Vector<stdClass>
 * but we do not (currently) parse recursively in depth so we cannot correctly identify array<int, array<string>>.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 * @author Mihai Stancu <stancu.t.mihai@gmail.com>
 */
class TypeTextParser
{
    const COLLECTION_INTERFACE = 'HH\\Collection';

    public static $typeMap = array(
        'HH\\bool' => Type::BUILTIN_TYPE_BOOL,
        'HH\\int' => Type::BUILTIN_TYPE_INT,
        'HH\\float' => Type::BUILTIN_TYPE_FLOAT,
        'HH\\double' => Type::BUILTIN_TYPE_FLOAT,
        'HH\\string' => Type::BUILTIN_TYPE_STRING,
        'callable' => Type::BUILTIN_TYPE_CALLABLE,
        'array' => Type::BUILTIN_TYPE_ARRAY,
        'null' => Type::BUILTIN_TYPE_NULL,
        'resource' => Type::BUILTIN_TYPE_RESOURCE,
    );

    /**
     * @param string $typeText
     *
     * @return Type
     */
    public function parse($typeText)
    {
        if (!$typeText) {
            return;
        }

        if ($nullable = ('?' === $typeText[0])) {
            $typeText = substr($typeText, 1);
        }

        if (($type = $this->parseSimpleTypes($typeText, $nullable))
        || ($type = $this->parseContainerTypes($typeText, $nullable))) {
            return array($type);
        }
    }

    /**
     * @param string $typeText
     * @param bool   $nullable
     * @param Type   $innerKeyType
     * @param Type   $innerValueType
     *
     * @return Type|null
     */
    public function parseSimpleTypes($typeText, $nullable = false, $innerKeyType = null, $innerValueType = null)
    {
        if (isset(static::$typeMap[$typeText])) {
            return new Type(
                static::$typeMap[$typeText],
                $nullable,
                null,
                'array' === static::$typeMap[$typeText],
                $innerKeyType,
                $innerValueType
            );
        }

        if (interface_exists($typeText, true) || class_exists($typeText, true)) {
            $class = new \ReflectionClass($typeText);

            return new Type(
                Type::BUILTIN_TYPE_OBJECT,
                $nullable,
                $typeText,
                $class->implementsInterface(static::COLLECTION_INTERFACE),
                $innerKeyType,
                $innerValueType
            );
        }
    }

    /**
     * @param string $typeText
     * @param bool   $nullable
     *
     * @return Type|null
     */
    protected function parseContainerTypes($typeText, $nullable = false)
    {
        if (false !== ($pos = strpos($typeText, '<'))) {
            $container = substr($typeText, 0, $pos);
            $contents = substr($typeText, $pos + 1, -1);
            $contents = explode(', ', $contents);

            if (count($contents) > 2) {
                return;
            }

            if (count($contents) === 2) {
                $innerKeyType = $this->parseSimpleTypes($contents[0]);
                $innerValueType = $this->parseSimpleTypes($contents[1]);
            } else {
                $innerKeyType = new Type(Type::BUILTIN_TYPE_INT);
                $innerValueType = $this->parseSimpleTypes($contents[0]);
            }

            if ('array' === $container || interface_exists($container, true) ||  class_exists($container, true)) {
                $outerType = $this->parseSimpleTypes($container, $nullable, $innerKeyType, $innerValueType);

                return $outerType;
            }
        }
    }
}
