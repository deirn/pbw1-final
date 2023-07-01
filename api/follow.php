<?php

require_once __DIR__ . '/../index.php';
require_once __DIR__ . '/../controllers/database.php';

use Controllers\Database\Connection;

$type = $_POST['type'];
$follower = $_POST['follower'];
$following = $_POST['following'];

if ($type == 'follow') {
    if (Connection::get($follower, $following) == null) {
        Connection::create($follower, $following);
    }
} elseif ($type = 'unfollow') {
    $connection = Connection::get($follower, $following);
    $connection?->delete();
}

echo json_encode([
    'follower_count' => Connection::get_followers_count($following)
]);
