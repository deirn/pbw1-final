<?php

use Database\Controllers\Engagement;
use Database\Datagen\Output\GeneratedEngagements;

echo 'Starting engagement migration' . PHP_EOL;

$total = 0;

foreach (GeneratedEngagements::$output as $engagement) {
    Engagement::create($engagement['username'], $engagement['status_id']);
    $total++;
}

echo "Inserted {$total} engagements" . PHP_EOL;
