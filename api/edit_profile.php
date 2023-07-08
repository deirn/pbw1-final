<?php

use Database\Controllers\User;

$username = $_POST['username'];
$display_name = $_POST['display_name'];
$bio = $_POST['bio'];

header('Content-Type: application/json');

if ($display_name == "") {
    echo json_encode([
        "error_field" => "display_name",
        "error_msg" => "Missing display name"
    ]);
} else {
    User::edit($username, $display_name, $bio);
    echo json_encode([]);
}
