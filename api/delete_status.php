<?php

use Database\Controllers\Status;

$status_id = $_POST['status_id'];

Status::get($status_id)?->delete();

header('Content-Type: application/json');
echo json_encode([]);
