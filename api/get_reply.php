<?php

use Database\Controllers\Engagement;
use Database\Controllers\Status;

$parent_status_id = $_GET['parent_status_id'];
$id_before = $_GET['id_before'];

$statuses = Status::get_child($parent_status_id, $id_before);
$result = [];

foreach ($statuses as $status) {
    $entry = [... (array)$status];
    $entry['liked_by_client'] = Engagement::is_status_liked($_SESSION['username'], $status->status_id);
    $result[] = $entry;
}

echo json_encode($result);
