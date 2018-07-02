<?php

return PhpCsFixer\Config::create()
    ->setUsingCache(false)
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => true,
        'no_whitespace_in_blank_line' => true,
        'no_unused_imports' => true,
        'blank_line_before_return' => true,
        'binary_operator_spaces' => true,
        'cast_spaces' => true,
        'declare_equal_normalize' => true,
        'method_argument_space' => true,
        'method_separation' => true,
        'no_leading_namespace_whitespace' => true,
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_whitespace_before_comma_in_array' => true,
        'trim_array_spaces' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_align' => true,
        'phpdoc_scalar' => true,
        'phpdoc_indent' => true,
        'phpdoc_order' => true,
        'phpdoc_separation' => true,
        'no_empty_statement' => true,
        'concat_space' => ['spacing' => 'one'],
        'no_multiline_whitespace_before_semicolons' => true,
        'no_leading_import_slash' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
    );
