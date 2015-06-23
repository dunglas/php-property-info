<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Extractors;

use PropertyInfo\TypeExtractorInterface;
use PropertyInfo\TypeInfoParsers\HhvmTypeInfoParser;
use PropertyInfo\TypeInfoParsers\PhpTypeInfoParser;

/**
 * Native Extractor.
 *
 * @author Mihai Stancu <stancu.t.mihai@gmail.com>
 */
abstract class NativeExtractor implements TypeExtractorInterface
{
    /**
     * @var  PhpTypeInfoParser|HhvmTypeInfoParser
     */
    protected $typeInfoParser;

    public function __construct()
    {
        if (defined('HHVM_VERSION')) {
            $this->typeInfoParser = new HhvmTypeInfoParser();
        } else {
            $this->typeInfoParser = new PhpTypeInfoParser();
        }
    }
}
