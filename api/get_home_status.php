<?php

require_once __DIR__ . '/../index.php';

use Controllers\Database\Engagement;
use Controllers\Database\Status;

$username = $_GET['username'];
$id_before = $_GET['id_before'];

$statuses = Status::get_status_from_followed($username, $id_before);
$result = [];

foreach ($statuses as $status) {
    $entry = [... (array)$status];
    $entry['liked_by_client'] = Engagement::is_status_liked($_SESSION['username'], $status->status_id);
    $result[] = $entry;
}

echo json_encode($result);
