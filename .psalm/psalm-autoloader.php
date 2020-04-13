<?php

declare(strict_types=1);

$psalmDir = rtrim(__DIR__, '/');
$projectDir = rtrim(dirname($psalmDir), '/');

$psalmIncludes = [
    'wp-functions.php',
];

foreach ($psalmIncludes as $include) {
    /** @noinspection PhpIncludeInspection */
    require_once "{$psalmDir}/{$include}";
}

unset(
    $psalmDir,
    $psalmIncludes,
    $projectDir
);
