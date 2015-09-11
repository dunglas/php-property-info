<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\TypeInfoParsers;

use Doctrine\Common\Persistence\Mapping\ClassMetadataFactory;
use Doctrine\Common\Persistence\Mapping\MappingException;
use PropertyInfo\TypeInfoParserInterface;

/**
 * This class will extract type information available to Doctrine from the Metadata.
 *
 * @author Mihai Stancu <stancu.t.mihai@gmail.com>
 */
class DoctrineTypeInfoParser implements TypeInfoParserInterface
{
    use ContainerTypeInfoParser;

    const COLLECTION_INTERFACE = 'Doctrine\Common\Collections\Collection';

    protected static $types = [
        'boolean' => 'bool',
        'smallint' => 'int',
        'integer' => 'int',
        'bigint' => 'int',
        'decimal' => 'float',
        'text' => 'string',
        'guid' => 'string',
        'blob' => 'resource',
        'binary' => 'resource',
        'date' => '\DateTime',
        'datetime' => '\DateTime',
        'datetimetz' => '\DateTime',
        'time' => '\DateTime',
        'array' => 'array',
        'simple_array' => 'array',
        'json_array' => 'array',
    ];

    /**
     * @var ClassMetadataFactory
     */
    private $classMetadataFactory;

    public function __construct(ClassMetadataFactory $classMetadataFactory)
    {
        $this->classMetadataFactory = $classMetadataFactory;
    }

    /**
     * @param \ReflectionProperty $property
     *
     * @return string|null
     */
    public function getPropertyType(\ReflectionProperty $property)
    {
        try {
            $entityClass = $property->getDeclaringClass()->getName();
            $meta = $this->classMetadataFactory->getMetadataFor($entityClass);

            $name = $property->getName();

            if ($meta->hasField($name)) {
                $info = $meta->getTypeOfField($name);

                return $info;
            }

            if ($meta->hasAssociation($name)) {
                $info = $meta->getAssociationTargetClass($name);

                if ($meta->isSingleValuedAssociation($name)) {
                    return $info;
                } else {
                    return 'Doctrine\Common\Collections\Collection<'.$info.'>';
                }
            }
        } catch (MappingException $exception) {
            /* do nothing */
        }
    }
}
