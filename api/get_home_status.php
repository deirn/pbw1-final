<?php

use Database\Controllers\Engagement;
use Database\Controllers\Status;

$username = $_SESSION['username'];
$id_before = $_GET['id_before'];

$statuses = Status::get_ancestors_from_user_and_followed($username, $id_before);
$result = [];

foreach ($statuses as $status) {
    $entry = [... (array)$status];
    $entry['liked_by_client'] = Engagement::is_status_liked($_SESSION['username'], $status->status_id);
    $result[] = $entry;
}

echo json_encode($result);
