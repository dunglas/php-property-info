<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Tests\DoctrineExtractors\Data;

/**
 * @Entity
 */
class DoctrineDummy
{
    /**
     * @Id
     * @Column(type="smallint")
     */
    public $id;
    /**
     * @ManyToOne(targetEntity="DoctrineRelation")
     */
    public $foo;
    /**
     * @ManyToMany(targetEntity="DoctrineRelation")
     */
    public $bar;
    /**
     * @Column(type="guid")
     */
    protected $guid;
    /**
     * @Column(type="time")
     */
    private $time;
    /**
     * @Column(type="json_array")
     */
    private $json;
    /**
     * @Column(type="boolean")
     */
    private $bool;
    /**
     * @Column(type="binary")
     */
    private $binary;

    public $notMapped;
}
