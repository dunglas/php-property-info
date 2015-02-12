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

    public function extractShortDescription(\ReflectionProperty $reflectionProperty)
    {
        if (!($docBlock = $this->getDocBlock($reflectionProperty))) {
            return;
        }

        return $docBlock->getShortDescription();
    }

    public function extractLongDescription(\ReflectionProperty $reflectionProperty)
    {
        if (!($docBlock = $this->getDocBlock($reflectionProperty))) {
            return;
        }

        return $docBlock->getLongDescription()->getContents();
    }


    public function extractType(\ReflectionProperty $reflectionProperty)
    {
        if (!($docBlock = $this->getDocBlock($reflectionProperty))) {
            return;
        }

        return $docBlock->getTagsByName('var');
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
}
