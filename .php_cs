<?php

$finder = PhpCsFixer\Finder::create()
    ->in(array(__DIR__))
;

return PhpCsFixer\Config::create()
    ->setRules(array(
        '@Symfony' => true,
        'unalign_double_arrow' => false,
        'unalign_equals' => false,
        'align_double_arrow' => true,
	'blank_line_after_opening_tag' => true,
        'ordered_imports' => true,
    ))
    ->setUsingCache(false)
    ->finder($finder)
;
