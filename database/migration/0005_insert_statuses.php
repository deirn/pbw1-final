<?php

use Database\Controllers\Status;
use Database\Datagen\Output\GeneratedStatusAncestors;
use Database\Datagen\Output\GeneratedStatusReplies;

$ancestors = GeneratedStatusAncestors::$output;
usort($ancestors, 'status_sorter');

$total = 0;

foreach ($ancestors as $status) {
    Status::create_ancestor($status['username'], $status['status_content'], $status['created_at']);
    $total++;
}

echo "Inserted {$total} ancestor statuses" . PHP_EOL;


$replies = GeneratedStatusReplies::$output;
usort($replies, 'status_sorter');

$total = 0;

foreach ($replies as $status) {
    Status::create_child($status['username'], $status['parent_status_id'], $status['status_content'], $status['created_at']);
    $total++;
}

echo "Inserted {$total} reply statuses" . PHP_EOL;


function status_sorter($a, $b): int
{
    $time_a = strtotime($a['created_at']);
    $time_b = strtotime($b['created_at']);
    return $time_a - $time_b;
}
