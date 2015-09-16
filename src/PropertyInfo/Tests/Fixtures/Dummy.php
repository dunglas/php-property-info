<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Tests\Fixtures;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class Dummy extends ParentDummy
{
    /**
     * @var string This is bar.
     */
    private $bar;
    /**
     * Should be used.
     *
     * @var int Should be ignored.
     */
    protected $baz;
    /**
     * @var \DateTime
     */
    public $bal;
    /**
     * @var ParentDummy
     */
    public $parent;
    /**
     * @var \DateTime[]
     */
    public $collection;

    /**
     * A.
     *
     * @return int
     */
    public function getA()
    {
    }

    /**
     * B.
     *
     * @param ParentDummy|null $parent
     */
    public function setB(ParentDummy $parent = null)
    {
    }
}
