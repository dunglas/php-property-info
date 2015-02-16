PHP Property Info
=================

PHP doesn't support explicit type definition. This is annoying. Especially when doing meta programming. Various libraries
including but not limited to Doctrine ORM and the Symfony Validator provide their own type managing system.
This library extracts various information including the type and documentation from PHP class property from metadata of
popular sources:

* Setter method with type hint
* PHPDoc DocBlock
* Doctrine ORM mapping (annotation, XML, YML or custom format)

[![Build Status](https://travis-ci.org/dunglas/php-property-info.svg?branch=master)](https://travis-ci.org/dunglas/php-property-info)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/29b845cf-106d-45b4-99af-271f2dc3f7d5/mini.png)](https://insight.sensiolabs.com/projects/29b845cf-106d-45b4-99af-271f2dc3f7d5)

TODO
----

* [ ] Symfony Validator Component support
