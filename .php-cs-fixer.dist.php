<?php

$config = new PhpCsFixer\Config();
return $config->setRules([
        '@PSR12' => true,
        'trailing_comma_in_multiline' => ['elements' => ['arrays']],
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sort_algorithm' => 'length'],
    ]);