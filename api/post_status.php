<?php

use Database\Controllers\DB;
use Database\Controllers\Status;
use Database\Controllers\User;

$username = $_SESSION['username'];
$content = $_POST['content'];
$created_at = DB::create_date_time_string();

$user = User::get($username);
$status = Status::create_ancestor($username, $content, $created_at);
$status->display_name = $user->display_name;
$status->avatar = $user->avatar;

header('Content-Type: application/json');
echo json_encode([
    ...(array)$status,
    'liked_by_client' => false
]);
