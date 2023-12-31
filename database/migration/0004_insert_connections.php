<?php

use Database\Controllers\Connection;
use Database\Datagen\Output\GeneratedConnections;

echo "Starting connection migration" . PHP_EOL;

$total = 0;

foreach (GeneratedConnections::$output as $connection) {
    Connection::create($connection['follower_username'], $connection['following_username']);
    $total++;
}

echo "Inserted {$total} connections" . PHP_EOL;
