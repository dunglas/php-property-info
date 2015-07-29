<?php

$header = <<<EOF
(c) Kévin Dunglas <dunglas@gmail.com>

This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.
EOF;

Symfony\CS\Fixer\Contrib\HeaderCommentFixer::setHeader($header);

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->notName('Php7Data.php')
    ->in(array(__DIR__))
;

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::SYMFONY_LEVEL)
    ->fixers(array(
        '-psr0',
        'header_comment',
        'newline_after_open_tag',
        'ordered_use',
        'short_array_syntax',
    ))
    ->setUsingCache(true)
    ->finder($finder)
;
