<?php

require_once __DIR__ . '/../datagen/output/connection.php';

use Controllers\Database\Connection;
use Datagen\Output\GeneratedConnections;

$total = 0;

foreach (GeneratedConnections::$output as $connection) {
    Connection::create($connection['follower_username'], $connection['following_username']);
    $total++;
}

echo "Inserted {$total} connections" . PHP_EOL;
