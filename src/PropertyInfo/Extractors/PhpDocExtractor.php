<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Extractors;

use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\FileReflector;
use PropertyInfo\DescriptionExtractorInterface;
use PropertyInfo\Type;
use PropertyInfo\TypeExtractorInterface;

/**
 * PHPDoc Extractor.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class PhpDocExtractor implements DescriptionExtractorInterface, TypeExtractorInterface
{
    /**
     * @var FileReflector[]
     */
    private static $fileReflectors = [];
    /**
     * @var DocBlock[]
     */
    private static $docBlocks = [];
    /**
     * @var array
     */
    private static $nativeTypes = [
        'int' => true,
        'bool' => true,
        'float' => true,
        'string' => true,
        'array' => true,
        'object' => true,
        'null' => true,
        'resource' => true,
        'callable' => true,
    ];

    public function extractShortDescription(\ReflectionProperty $reflectionProperty)
    {
        if (!($docBlock = $this->getDocBlock($reflectionProperty))) {
            return;
        }

        $shortDescription = $docBlock->getShortDescription();
        if ($shortDescription) {
            return $shortDescription;
        }

        foreach ($docBlock->getTagsByName('var') as $var) {
            $parsedDescription = $var->getParsedDescription();

            if (isset($parsedDescription[0])) {
                return $parsedDescription[0];
            }
        }
    }

    public function extractLongDescription(\ReflectionProperty $reflectionProperty)
    {
        if (!($docBlock = $this->getDocBlock($reflectionProperty))) {
            return;
        }

        return $docBlock->getLongDescription()->getContents();
    }

    public function extractTypes(\ReflectionProperty $reflectionProperty)
    {
        if (!($docBlock = $this->getDocBlock($reflectionProperty))) {
            return;
        }

        $types = [];
        foreach ($docBlock->getTagsByName('var') as $var) {
            foreach ($var->getTypes() as $docType) {
                $type = $this->createType($docType);
                if (null !== $type) {
                    $types[] = $type;
                }
            }
        }

        return $types;
    }

    /**
     * Gets the FileReflector associated with the class.
     *
     * @param \ReflectionClass $reflectionClass
     *
     * @return FileReflector|null
     */
    private function getFileReflector(\ReflectionClass $reflectionClass)
    {
        if (!($fileName = $reflectionClass->getFileName())) {
            return;
        }

        if (isset(self::$fileReflectors[$fileName])) {
            return self::$fileReflectors[$fileName];
        }

        self::$fileReflectors[$fileName] = new FileReflector($fileName);
        self::$fileReflectors[$fileName]->process();

        return self::$fileReflectors[$fileName];
    }

    /**
     * Gets the DocBlock of this property.
     *
     * @param \ReflectionProperty $reflectionProperty
     *
     * @return DocBlock|null
     */
    private function getDocBlock(\ReflectionProperty $reflectionProperty)
    {
        $propertyHash = spl_object_hash($reflectionProperty);

        if (isset(self::$docBlocks[$propertyHash])) {
            return self::$docBlocks[$propertyHash];
        }

        $reflectionClass = $reflectionProperty->getDeclaringClass();
        if ($fileReflector = $this->getFileReflector($reflectionClass)) {
            foreach ($fileReflector->getClasses() as $class) {
                $className = $class->getName();
                if ('\\' === $className[0]) {
                    $className = substr($className, 1);
                }

                if ($className === $reflectionClass->getName()) {
                    foreach ($class->getProperties() as $property) {
                        // strip the $ prefix
                        $propertyName = substr($property->getName(), 1);

                        if ($propertyName === $reflectionProperty->getName()) {
                            return self::$docBlocks[$propertyHash] = $property->getDocBlock();
                        }
                    }
                }
            }
        }

        return self::$docBlocks[$propertyHash] = null;
    }

    /**
     * Creates a {@see Type} from a PHPDoc type.
     *
     * @param string $docType
     *
     * @return Type|null
     */
    private function createType($docType)
    {
        // Cannot guess
        if (!$docType || 'mixed' === $docType) {
            return;
        }

        if ($collection = '[]' === substr($docType, -2)) {
            $docType = substr($docType, 0, -2);
        }

        $array = 'array' === $docType;

        $type = new Type();
        if ($collection || $array) {
            $type->setCollection(true);
            $type->setType('array');

            if (!$array && 'mixed' !== $docType) {
                $docType = $this->normalizeType($docType);

                $collectionType = new Type();
                $collectionType->setCollection(false);
                $this->populateType($collectionType, $docType);

                $type->setCollectionType($collectionType);
            }

            return $type;
        }

        $docType = $this->normalizeType($docType);
        $type->setCollection(false);
        $this->populateType($type, $docType);

        return $type;
    }

    /**
     * Normalizes the type.
     *
     * @param string $docType
     *
     * @return string
     */
    private function normalizeType($docType)
    {
        switch ($docType) {
            case 'integer':
                return 'int';

            // real is not handle by the PHPDoc standard, so we ignore it
            case 'double':
                return 'float';

            case 'callback':
                return 'callable';

            case 'void':
                return 'null';

            default:
                return $docType;
        }
    }

    /**
     * Populates type.
     *
     * @param Type   $type
     * @param string $docType
     */
    private function populateType(Type $type, $docType)
    {
        if (isset(self::$nativeTypes[$docType])) {
            $type->setType($docType);

            return;
        }

        $type->setType('object');
        $type->setClass($docType);
    }
}
