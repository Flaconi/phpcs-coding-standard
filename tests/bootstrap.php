<?php

declare(strict_types=1);

use PHP_CodeSniffer\Autoload;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/squizlabs/php_codesniffer/autoload.php';

//fix some strange issues with the autoload from php_codesniffer in case of an error

$class = new ReflectionClass(Autoload::class);

$property = $class->getProperty('loadedClasses');
$property->setAccessible(true);
$loadedClass                                                                                                         = $property->getValue();
$loadedClass[realpath(__DIR__ . '/../FlaconiCodingStandard/Sniffs/Test/UseMethodPrefixInTestcaseSniff.php')]         = 'FlaconiCodingStandard\Sniffs\Test\UseMethodPrefixInTestcaseSniff';
$loadedClass[realpath(__DIR__ . '/../FlaconiCodingStandard/Sniffs/Test/UseStaticCallsForAssertInTestcaseSniff.php')] = 'FlaconiCodingStandard\Sniffs\Test\UseStaticCallsForAssertInTestcaseSniff';

$property->setValue($loadedClass);
