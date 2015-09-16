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
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use PropertyInfo\Type;
use PropertyInfo\TypeExtractorInterface;

/**
 * Extracts data using Doctrine ORM and ODM metadata.
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

    /**
     * {@inheritdoc}
     */
    public function extractTypes($class, $property)
    {
        try {
            $metadata = $this->classMetadataFactory->getMetadataFor($class);
        } catch (MappingException $exception) {
            return;
        }

        if ($metadata->hasAssociation($property)) {
            $class = $metadata->getAssociationTargetClass($property);

            if ($metadata->isSingleValuedAssociation($property)) {
                if ($metadata instanceof ClassMetadataInfo) {
                    $nullable = isset($metadata->discriminatorColumn['nullable']) ? $metadata->discriminatorColumn['nullable'] : false;
                } else {
                    $nullable = false;
                }

                return [new Type(Type::BUILTIN_TYPE_OBJECT, $nullable, $class)];
            }

            return [new Type(
                Type::BUILTIN_TYPE_OBJECT,
                false,
                'Doctrine\Common\Collections\Collection',
                true,
                new Type(Type::BUILTIN_TYPE_INT),
                new Type(Type::BUILTIN_TYPE_OBJECT, false, $class)
            )];
        }

        if ($metadata->hasField($property)) {
            $typeOfField = $metadata->getTypeOfField($property);
            if ($metadata instanceof ClassMetadataInfo) {
                $nullable = $metadata->isNullable($property);
            } else {
                $nullable = false;
            }

            switch ($typeOfField) {
                case 'date':
                    // No break
                case 'datetime':
                    // No break
                case 'datetimetz':
                    // No break
                case 'time':
                    return [new Type(Type::BUILTIN_TYPE_OBJECT, $nullable, 'DateTime')];

                case 'array':
                    // No break
                case 'simple_array':
                    return [new Type(Type::BUILTIN_TYPE_ARRAY, $nullable, null, true, new Type(Type::BUILTIN_TYPE_INT))];

                case 'json_array':
                    return [new Type(Type::BUILTIN_TYPE_ARRAY, $nullable, null, true)];

                default:
                    return [new Type($this->getPhpType($typeOfField), $nullable)];
            }
        }
    }

    /**
     * Gets the corresponding built-in PHP type.
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
                return Type::BUILTIN_TYPE_INT;

            case 'decimal':
                return Type::BUILTIN_TYPE_FLOAT;

            case 'text':
                // No break
            case 'guid':
                return Type::BUILTIN_TYPE_STRING;

            case 'boolean':
                return Type::BUILTIN_TYPE_BOOL;

            case 'blob':
                // No break
            case 'binary':
                return Type::BUILTIN_TYPE_RESOURCE;

            default:
                return $doctrineType;
        }
    }
}
