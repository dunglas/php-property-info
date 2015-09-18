<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Extractors;

use Doctrine\Common\Persistence\Mapping\ClassMetadataFactory;
use PropertyInfo\Type;
use PropertyInfo\TypeExtractorInterface;
use PropertyInfo\TypeInfoParsers\DoctrineTypeInfoParser;

/**
 * Doctrine ORM and ODM Extractor.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class DoctrineExtractor implements TypeExtractorInterface
{
    /**
     * @var DoctrineTypeInfoParser
     */
    protected $typeInfoParser;

    public function __construct(ClassMetadataFactory $classMetadataFactory)
    {
        $this->typeInfoParser = new DoctrineTypeInfoParser($classMetadataFactory);
    }

    /**
     * @param \ReflectionProperty $property
     *
     * @return Type[]
     */
    public function extractTypes(\ReflectionProperty $property)
    {
        $typeInfo = $this->typeInfoParser->getPropertyType($property);

        return $this->typeInfoParser->parse($typeInfo);
    }
}
