<?php

require_once __DIR__ . '/../index.php';
require_once __DIR__ . '/../controllers/database.php';

use Controllers\Database\User;

$user_cookie_base64 = $_COOKIE['login'] ?? null;

if ($user_cookie_base64 == null) {
    header("Location: /auth");
} else {
    $user_cookie = json_decode(base64_decode($user_cookie_base64), true);
    $user = User::get_user($user_cookie['username']);

    if ($user != null && $user->password == $user_cookie['password']) {
        $_SESSION['username'] = $user->username;
        $_SESSION['display_name'] = $user->display_name;
        $_SESSION['avatar'] = $user->avatar;
        header("Refresh:0");
    } else {
        header("Location: /auth");
    }
}



