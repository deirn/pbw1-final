<?php

require_once __DIR__ . '/../datagen/output/user.php';

use Controllers\Database\User;
use Datagen\Output\GeneratedUsers;

$total = 0;

foreach (GeneratedUsers::$output as $user) {
    User::create($user["username"], $user["password"], $user["display_name"]);
    $total++;
}

echo "Inserted {$total} users" . PHP_EOL;
