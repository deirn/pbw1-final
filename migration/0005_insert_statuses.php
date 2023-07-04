<?php

require_once __DIR__ . '/../datagen/output/status_ancestor.php';

use Controllers\Database\Status;
use Datagen\Output\GeneratedStatusAncestors;

$ancestors = GeneratedStatusAncestors::$output;

usort($ancestors, function ($a, $b) {
    $time_a = strtotime($a['created_at']);
    $time_b = strtotime($b['created_at']);
    return $time_a - $time_b;
});

$total = 0;

foreach ($ancestors as $status) {
    Status::create_ancestor($status['username'], $status['status_content'], $status['created_at']);
    $total++;
}

echo "Inserted {$total} ancestor statuses" . PHP_EOL;
