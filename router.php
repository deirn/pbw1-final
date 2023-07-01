<?php

require_once __DIR__ . '/index.php';
require_once __DIR__ . '/components/index.php';
require_once __DIR__ . '/controllers/database.php';

$request = rtrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');

if ($request == '/auth') {
    require __DIR__ . '/views/auth.php';
} elseif (!isset($_SESSION['username']) || !isset($_COOKIE['login'])) {
    require __DIR__ . '/api/auth.php';
} else {
    $slug_matches = [];

    switch ($request) {
        case '':
            header("Location: /home");
            break;

        case '/home':
            require __DIR__ . '/views/home.php';
            break;

        case '/explore':
            require __DIR__ . '/views/explore.php';
            break;

        case '/search':
            require __DIR__ . '/views/search.php';
            break;

        case 1 == preg_match("/^\/profile\/([a-z0-9_]{5,15})$/", $request, $slug_matches):
            require __DIR__ . '/views/profile.php';
            break;

        default:
            http_response_code(404);
            require __DIR__ . '/views/404.php';
            break;
    }
}
