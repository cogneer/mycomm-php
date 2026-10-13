<?php

declare(strict_types=1);

use MyComm\Generator\Generator;

require dirname(__DIR__).'/vendor/autoload.php';

$written = (new Generator(dirname(__DIR__)))->run();

echo 'Generated '.implode(', ', array_map(static fn (string $what, int $count): string => "{$count} {$what}", array_keys($written), $written)).".\n";
