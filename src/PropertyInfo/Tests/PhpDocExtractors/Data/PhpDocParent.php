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
class PhpDocParent
{
    /**
     * Short description.
     *
     * Long description.
     */
    public $foo;
    /**
     * @var float
     */
    public $foo2;
    /**
     * @var callback
     */
    public $foo3;
    /**
     * @var void
     */
    public $foo4;
    /**
     * @var mixed
     */
    public $foo5;
    /**
     * @var \SplFileInfo[]|resource
     */
    public $files;
}
