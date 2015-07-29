<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Tests\PhpDocExtractors\Data;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class PhpDocDummy extends PhpDocParent
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
     * @var PhpDocParent
     */
    public $parent;
    /**
     * @var \DateTime[]
     */
    public $collection;
}
