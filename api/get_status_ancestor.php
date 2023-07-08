<?php

use Database\Controllers\Status;

$status_id = $_GET['status_id'];

header('Content-Type: application/json');
echo json_encode([...Status::get_ancestors($status_id)]);
