<?php

use Database\Controllers\User;

global $request_uri;

$user_cookie_base64 = $_COOKIE['login2'] ?? null;

if ($user_cookie_base64 == null) {
    header("Location: /auth");
} else {
    $user_cookie = json_decode(base64_decode($user_cookie_base64), true);
    $user = User::get_by_id($user_cookie['id']);

    if ($user != null && $user->password == $user_cookie['password']) {
        $_SESSION['username'] = $user->username;

        if ($request_uri == '/api/auth') {
            header("Location: /");
        } else {
            header("Refresh:0");
        }
    } else {
        header("Location: /auth");
    }
}
