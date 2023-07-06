<?php

require_once __DIR__ . '/../index.php';

use Controllers\Database\Engagement;

$username = $_SESSION['username'];
$status_id = $_POST['status_id'];

$engagement = Engagement::get($username, $status_id);
$liked = false;

if ($engagement != null) {
    $engagement->delete();
} else {
    Engagement::create($username, $status_id);
    $liked = true;
}

echo json_encode([
    'liked' => $liked,
    'new_like_count' => Engagement::count($status_id)
]);
