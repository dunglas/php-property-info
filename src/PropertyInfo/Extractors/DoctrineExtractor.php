<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Extractors;

use Doctrine\Common\Persistence\Mapping\ClassMetadataFactory;
use Doctrine\Common\Persistence\Mapping\MappingException;
use PropertyInfo\Type;
use PropertyInfo\TypeExtractorInterface;

/**
 * Doctrine ORM and ODM Extractor.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class DoctrineExtractor implements TypeExtractorInterface
{
    /**
     * @var ClassMetadataFactory
     */
    private $classMetadataFactory;

    public function __construct(ClassMetadataFactory $classMetadataFactory)
    {
        $this->classMetadataFactory = $classMetadataFactory;
    }

    public function extractTypes(\ReflectionProperty $reflectionProperty)
    {
        $className = $reflectionProperty->getDeclaringClass()->getName();

        try {
            $metadata = $this->classMetadataFactory->getMetadataFor($className);
        } catch (MappingException $exception) {
            return;
        }

        $type = new Type();
        $propertyName = $reflectionProperty->getName();

        if ($metadata->hasAssociation($propertyName)) {
            $class = $metadata->getAssociationTargetClass($propertyName);

            if ($metadata->isSingleValuedAssociation($propertyName)) {
                $type->setCollection(false);
                $type->setType('object');
                $type->setClass($class);
            } else {
                $type->setCollection(true);
                $type->setType('object');
                $type->setClass('Doctrine\Common\Collections\Collection');

                $collectionType = new Type();
                $collectionType->setCollection(false);
                $collectionType->setType('object');
                $collectionType->setClass($class);

                $type->setCollectionType($collectionType);
            }

            return [$type];
        }

        if ($metadata->hasField($propertyName)) {
            $typeOfField = $metadata->getTypeOfField($propertyName);

            switch ($typeOfField) {
                case 'date':
                    // No break
                case 'datetime':
                    // No break
                case 'datetimetz':
                    // No break
                case 'time':
                    $type->setType('object');
                    $type->setClass('DateTime');
                    $type->setCollection(false);

                    return [$type];

                case 'array':
                    // No break
                case 'simple_array':
                    // No break
                case 'json_array':
                    $type->setType('array');
                    $type->setCollection(true);

                    return [$type];

                default:
                    $type->setType($this->getPhpType($typeOfField));
                    $type->setCollection(false);

                    return [$type];
            }
        }
    }

    /**
     * Gets the corresponding PHP type.
     *
     * @param string $doctrineType
     *
     * @return string
     */
    private function getPhpType($doctrineType)
    {
        switch ($doctrineType) {
            case 'smallint':
                // No break
            case 'bigint':
                // No break
            case 'integer':
                return 'int';

            case 'decimal':
                return 'float';

            case 'text':
                // No break
            case 'guid':
                return 'string';

            case 'boolean':
                return 'bool';

            case 'blob':
                // No break
            case 'binary':
                return 'resource';

            default:
                return $doctrineType;
        }
    }
}
