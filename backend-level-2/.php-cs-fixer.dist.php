<?php

declare(strict_types=1);

$rules = [
    '@PHP80Migration' => true,
    '@PHP80Migration:risky' => true,
    '@PSR2' => true,
    '@PhpCsFixer' => true,
    '@PhpCsFixer:risky' => true,
    '@Symfony' => true,
    '@Symfony:risky' => true,
    'declare_strict_types' => true,
    'header_comment' => ['header' => ''],
    'list_syntax' => ['syntax' => 'short'],
    'multiline_whitespace_before_semicolons' => ['strategy' => 'new_line_for_chained_calls'],
    'native_function_invocation' => true,
    'no_unreachable_default_argument_value' => true,
    'php_unit_test_case_static_method_calls' => ['call_type' => 'self'],
    'phpdoc_to_comment' => false,
    'static_lambda' => true,
    'strict_comparison' => true,
    'strict_param' => false,
];

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/tests')
    ->in(__DIR__.'/src')
    ->append([
        __FILE__,
    ])
;

return (new PhpCsFixer\Config())
    ->setRules($rules)
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ;
