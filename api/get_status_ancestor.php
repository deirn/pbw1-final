<?php

use Database\Controllers\Status;

$status_id = $_GET['status_id'];

echo json_encode([...Status::get_ancestors($status_id)]);
