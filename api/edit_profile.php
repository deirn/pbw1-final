<?php

require_once __DIR__ . '/../index.php';
require_once __DIR__ . '/../controllers/database.php';

use Controllers\Database\User;

$username = $_POST['username'];
$display_name = $_POST['display_name'];
$bio = $_POST['bio'];

if ($display_name == "") {
    echo json_encode([
        "error_field" => "display_name",
        "error_msg" => "Missing display name"
    ]);
} else {
    User::edit($username, $display_name, $bio);
    echo json_encode([]);
}
