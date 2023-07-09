<?php

use Database\Controllers\User;
use Database\Datagen\Output\GeneratedBios;
use Database\Datagen\Output\GeneratedUsers;

echo 'Starting user migration' . PHP_EOL;

$avatar_dir = __DIR__ . '/../../public/assets/media/avatar';
array_map('unlink', glob("$avatar_dir/*"));
rmdir($avatar_dir);
mkdir($avatar_dir);

$total = 0;

foreach (GeneratedUsers::$output as $user) {
    $username = $user["username"];

    $avatar_url = "https://i.pravatar.cc/400?u={$username}";
    echo "  Downloading avatar from {$avatar_url}" . PHP_EOL;

    $avatar_data = file_get_contents($avatar_url);
    $avatar_hash = hash('sha256', $avatar_data);

    file_put_contents($avatar_dir . "/{$avatar_hash}", $avatar_data);

    User::create($username, $user["password"], $user["display_name"], GeneratedBios::$output[$username], $avatar_hash);
    $total++;
}

echo "Inserted {$total} users" . PHP_EOL;
