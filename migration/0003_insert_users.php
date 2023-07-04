<?php

require_once __DIR__ . '/../datagen/output/bio.php';
require_once __DIR__ . '/../datagen/output/user.php';

use Controllers\Database\User;
use Datagen\Output\GeneratedBios;
use Datagen\Output\GeneratedUsers;

$total = 0;

foreach (GeneratedUsers::$output as $user) {
    $username = $user["username"];
    User::create($username, $user["password"], $user["display_name"], GeneratedBios::$output[$username]);
    $total++;
}

echo "Inserted {$total} users" . PHP_EOL;
