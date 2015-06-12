# PHP Property Info

PHP doesn't support explicit type definition. This is annoying, especially when doing meta programming.
Various libraries including but not limited to Doctrine ORM and the Symfony Validator provide their own type managing
system.
This library extracts various information including the type and documentation from PHP class property from metadata of
popular sources:

* Setter method with type hint
* PHPDoc DocBlock
* Doctrine ORM mapping (annotation, XML, YML or custom format)

[![Build Status](https://travis-ci.org/dunglas/php-property-info.svg?branch=master)](https://travis-ci.org/dunglas/php-property-info)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/29b845cf-106d-45b4-99af-271f2dc3f7d5/mini.png)](https://insight.sensiolabs.com/projects/29b845cf-106d-45b4-99af-271f2dc3f7d5)

PHP Property info is part of the [API Platform](http://api-platform.com) framework.

## Installation

Use [Composer](http://getcomposer.org) to install the library:

    composer require dunglas/php-property-info

To use the PHPDoc extractor, install the [phpDocumentator's Reflection](https://github.com/phpDocumentor/Reflection) library.
To use the Doctrine extractor, install the [Doctrine ORM](http://www.doctrine-project.org/projects/orm.html).

## Usage

```php
<?php

// Use Composer autoload
require 'vendor/autoload.php';

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;

// PropoertyInfo uses
use PropertyInfo\Extractors\DoctrineExtractor;
use PropertyInfo\Extractors\PhpDocExtractor;
use PropertyInfo\Extractors\SetterExtractor;
use PropertyInfo\PropertyInfo;

/**
 * @Entity
 */
class MyTestClass
{
    /**
     * @Id
     * @Column(type="integer")
     */
    public $id;
    /**
     * This is a date (short description).
     *
     * With a long description.
     *
     * @var \DateTime
     */
    public $foo;
    private $bar;

    public function setBar(\SplFileInfo $bar)
    {
        $this->bar = $bar;
    }
}

// Doctrine initialization (necessary only to use the Doctrine Extractor)
$config = Setup::createAnnotationMetadataConfiguration([__DIR__], true);
$entityManager = EntityManager::create([
    'driver' => 'pdo_sqlite',
    // ...
], $config);

$doctrineExtractor = new DoctrineExtractor($entityManager->getMetadataFactory());
$phpDocExtractor = new PhpDocExtractor();
$setterExtractor = new SetterExtractor();

$propertyInfo = new PropertyInfo([$doctrineExtractor, $setterExtractor, $phpDocExtractor], [$phpDocExtractor]);

$fooProperty = new \ReflectionProperty('MyTestClass', 'foo');
var_dump($propertyInfo->getShortDescription($fooProperty));
var_dump($propertyInfo->getLongDescription($fooProperty));
var_dump($propertyInfo->getTypes($fooProperty));
var_dump($propertyInfo->getTypes(new \ReflectionProperty('MyTestClass', 'id')));
var_dump($propertyInfo->getTypes(new \ReflectionProperty('MyTestClass', 'bar')));
```

Output:

```
string(35) "This is a date (short description)."
string(24) "With a long description."
array(1) {
  [0] =>
  class PropertyInfo\Type#162 (4) {
    public $type =>
    string(6) "object"
    public $class =>
    string(8) "DateTime"
    public $collection =>
    bool(false)
    public $collectionType =>
    NULL
  }
}
array(1) {
  [0] =>
  class PropertyInfo\Type#172 (4) {
    public $type =>
    string(3) "int"
    public $class =>
    NULL
    public $collection =>
    bool(false)
    public $collectionType =>
    NULL
  }
}
array(1) {
  [0] =>
  class PropertyInfo\Type#165 (4) {
    public $type =>
    string(6) "object"
    public $class =>
    string(11) "SplFileInfo"
    public $collection =>
    bool(false)
    public $collectionType =>
    NULL
  }
}
```

Try it yourself using [Melody](http://melody.sensiolabs.org/):

    php melody.phar run https://gist.github.com/dunglas/0a4982e4635c9514aede

## TODO

* [ ] Symfony Validator Component support

## Credits

This library has been created by [Kévin Dunglas](http://dunglas.fr).
