<?php

use Database\Controllers\Engagement;
use Database\Controllers\Status;

$username = $_GET['username'];
$id_before = $_GET['id_before'];

$statuses = Status::get_from_user($username, $id_before);
$result = [];

foreach ($statuses as $status) {
    $entry = [... (array)$status];
    $entry['liked_by_client'] = Engagement::is_status_liked($_SESSION['username'], $status->status_id);
    $result[] = $entry;
}

header('Content-Type: application/json');
echo json_encode($result);
