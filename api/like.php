<?php

use Database\Controllers\Engagement;

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

header('Content-Type: application/json');
echo json_encode([
    'liked' => $liked,
    'new_like_count' => Engagement::count($status_id)
]);
