<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src');

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        'phpdoc_var_without_name' => false,
    ])
    ->setFinder($finder);
