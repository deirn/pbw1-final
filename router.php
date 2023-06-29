<?php

require_once __DIR__ . '/index.php';

$request = strtok($_SERVER['REQUEST_URI'], '?');

if ($request == '/auth') {
    require __DIR__ . '/views/auth.php';
} elseif (!isset($_SESSION['username']) || !isset($_COOKIE['login'])) {
    require __DIR__ . '/api/auth.php';
} else {
    switch ($request) {
        case '':
        case '/':
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

        default:
            http_response_code(404);
            require __DIR__ . '/views/404.php';
            break;
    }
}
