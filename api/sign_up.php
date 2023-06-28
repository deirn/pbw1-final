<?php

require_once __DIR__ . '/../index.php';
require_once __DIR__ . '/../controllers/database.php';

use Controllers\Database\User;

$username = $_POST['username'];
$password = $_POST['password'];
$repeat_password = $_POST['repeat_password'];
$display_name = $_POST['display_name'];

if ($display_name == "") {
    echo json_encode([
        "error_field" => "display_name",
        "error_msg" => "Missing"
    ]);
} elseif ($username == "") {
    echo json_encode([
        "error_field" => "username",
        "error_msg" => "Missing"
    ]);
} elseif ($password == "") {
    echo json_encode([
        "error_field" => "password",
        "error_msg" => "Missing"
    ]);
} elseif (!preg_match("/^[a-z0-9_]/", $username)) {
    echo json_encode([
        "error_field" => "username",
        "error_msg" => "Can only contains a-z 0-9 and _"
    ]);
} elseif (strlen($username) < 5 || strlen($username) > 15) {
    echo json_encode([
        "error_field" => "username",
        "error_msg" => "Must be 5-15 characters long"
    ]);
} elseif (User::get_user($username) != null) {
    echo json_encode([
        "error_field" => "username",
        "error_msg" => "Already Used!"
    ]);
} elseif (strlen($password) < 6) {
    echo json_encode([
        "error_field" => "password",
        "error_msg" => "Must be 6 Characters or More!"
    ]);
} elseif ($password != $repeat_password) {
    echo json_encode([
        "error_field" => "repeat_password",
        "error_msg" => "Doesn't Match!"
    ]);
} else {
    User::create_new_user($username, $password, $display_name);
    echo json_encode([]);
}
