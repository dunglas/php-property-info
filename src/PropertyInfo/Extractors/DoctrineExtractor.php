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
use Doctrine\ORM\Mapping\MappingException as OrmMappingException;
use Doctrine\DBAL\Types\Type as DBALType;
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
        } catch (OrmMappingException $exception) {
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
                case DBALType::DATE:
                case DBALType::DATETIME:
                case DBALType::DATETIMETZ:
                case 'vardatetime':
                case DBALType::TIME:
                    $type->setType('object');
                    $type->setClass('DateTime');
                    $type->setCollection(false);

                    return [$type];

                case DBALType::TARRAY:
                case DBALType::SIMPLE_ARRAY:
                case DBALType::JSON_ARRAY:
                    $type->setType('array');
                    $type->setCollection(true);

                    return [$type];

                default:
                    $builtinType = $this->getPhpType($typeOfField);

                    if (null === $builtinType) {
                        return;
                    }

                    $type->setType($builtinType);
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
     * @return string|null
     */
    private function getPhpType($doctrineType)
    {
        switch ($doctrineType) {
            case DBALType::SMALLINT:
            case DBALType::BIGINT:
            case DBALType::INTEGER:
                return 'int';

            case DBALType::FLOAT:
            case DBALType::DECIMAL:
                return 'float';

            case DBALType::STRING:
            case DBALType::TEXT:
            case DBALType::GUID:
                return 'string';

            case DBALType::BOOLEAN:
                return 'bool';

            case DBALType::BLOB:
                // No break
            case 'binary':
                return 'resource';
        }
    }
}
