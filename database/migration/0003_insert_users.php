<?php

use Database\Controllers\User;
use Database\Datagen\Output\GeneratedBios;
use Database\Datagen\Output\GeneratedUsers;

$total = 0;

foreach (GeneratedUsers::$output as $user) {
    $username = $user["username"];
    User::create($username, $user["password"], $user["display_name"], GeneratedBios::$output[$username]);
    $total++;
}

echo "Inserted {$total} users" . PHP_EOL;
