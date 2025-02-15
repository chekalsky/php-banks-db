<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('vendor')
    ->exclude('banks-db');

$config = new PhpCsFixer\Config();

return $config->setUsingCache(true)
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setCacheFile(__DIR__ . '/.php_cs.cache')
    ->setRules([
        '@PER-CS2.0' => true,
        'align_multiline_comment' => true,
        'blank_line_before_statement' => ['statements' => ['continue', 'declare', 'return', 'throw', 'try']],
        'class_attributes_separation' => ['elements' => ['method' => 'one']],
        'no_empty_statement' => true,
        'no_leading_namespace_whitespace' => true,
        'no_multiline_whitespace_around_double_arrow' => true,
        'multiline_whitespace_before_semicolons' => false,
        'no_unused_imports' => true,
        'no_unneeded_import_alias' => true,
        'no_whitespace_before_comma_in_array' => true,
        'no_whitespace_in_blank_line' => true,
        'single_import_per_statement' => true,
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => ['class', 'function', 'const'],
        ],
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_align' => true,
        'phpdoc_annotation_without_dot' => true,
        'phpdoc_indent' => true,
        'phpdoc_no_empty_return' => true,
        'phpdoc_no_useless_inheritdoc' => true,
        'phpdoc_order' => true,
        'phpdoc_return_self_reference' => ['replacements' => ['this' => 'self']],
        'phpdoc_scalar' => true,
        'phpdoc_separation' => ['skip_unlisted_annotations' => true],
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_trim' => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_types_order' => true,
        'phpdoc_var_annotation_correct_order' => true,
        'phpdoc_var_without_name' => true,
        'single_quote' => true,
        'short_scalar_cast' => true,
        'standardize_not_equals' => true,
        'ternary_operator_spaces' => true,
        'ternary_to_null_coalescing' => true,
        'trim_array_spaces' => true,
        'visibility_required' => ['elements' => ['property', 'method', 'const']],
        'yoda_style' => false,
        'no_trailing_comma_in_singleline' => true,
        'single_line_after_imports' => true,
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true,
        ],
        'clean_namespace' => true,
    ])
    ->setFinder($finder);
