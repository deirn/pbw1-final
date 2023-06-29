<?php

require_once __DIR__ . '/../datagen/output/user.php';

use Controllers\Database\User;
use Datagen\Output\GeneratedUsers;

foreach (GeneratedUsers::$output as $user) {
    User::create($user["username"], $user["password"], $user["display_name"]);
}

echo 'Inserted users' . PHP_EOL;
