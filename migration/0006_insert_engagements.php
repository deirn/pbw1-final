<?php

require_once __DIR__ . '/../datagen/output/engagement.php';

use Controllers\Database\Engagement;
use Datagen\Output\GeneratedEngagements;

$total = 0;

foreach (GeneratedEngagements::$output as $engagement) {
    Engagement::create($engagement['username'], $engagement['status_id']);
    $total++;
}

echo "Inserted {$total} engagements" . PHP_EOL;
