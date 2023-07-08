<?php

use Database\Controllers\DB;
use Database\Controllers\Status;

$client_username = $_SESSION['username'];

$status_id = $_POST['status_id'];
$status_content = $_POST['content'];

$status = Status::get($status_id);

if ($status->username == $client_username) {
    $updated_at = DB::create_date_time_string();
    $status->edit($status_content, $updated_at);
}

header('Content-Type: application/json');
echo json_encode($status);
