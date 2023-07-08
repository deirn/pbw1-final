<?php

use Database\Controllers\DB;
use Database\Controllers\Status;
use Database\Controllers\User;

$username = $_SESSION['username'];
$parent_status_id = $_POST['parent_status_id'];
$content = $_POST['content'];
$created_at = DB::create_date_time_string();

$user = User::get($username);
$status = Status::create_child($username, $parent_status_id, $content, $created_at);
$status->display_name = $user->display_name;

header('Content-Type: application/json');
echo json_encode([
    ...(array)$status,
    'liked_by_client' => false
]);
