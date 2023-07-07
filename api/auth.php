<?php

use Database\Controllers\User;

$user_cookie_base64 = $_COOKIE['login'] ?? null;

if ($user_cookie_base64 == null) {
    header("Location: /auth");
} else {
    $user_cookie = json_decode(base64_decode($user_cookie_base64), true);
    $user = User::get($user_cookie['username']);

    if ($user != null && $user->password == $user_cookie['password']) {
        $_SESSION['username'] = $user->username;
        header("Refresh:0");
    } else {
        header("Location: /auth");
    }
}
