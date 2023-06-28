<?php

require_once __DIR__ . '/../index.php';
require_once __DIR__ . '/../controllers/database.php';

use Controllers\Database\User;

$username = $_POST['username'];
$password = $_POST['password'];

if ($username == "") {
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
} else {
    $user = User::get_user($username);

    if ($user == null) {
        echo json_encode([
            "error_field" => "username",
            "error_msg" => "Unknown username"
        ]);
    } elseif (!password_verify($password, $user->password)) {
        echo json_encode([
            "error_field" => "password",
            "error_msg" => "Wrong password"
        ]);
    } else {
        setcookie('login', base64_encode(json_encode([
            "username" => $user->username,
            "password" => $user->password
        ])), time() + 60 * 60 * 24 * 30, '/');
        echo json_encode([]);
    }
}
