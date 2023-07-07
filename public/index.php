<?php

require_once __DIR__ . '/../index.php';
require_once __DIR__ . '/../components/index.php';

$page_title = '';
$slug_matches = [];
$is_404 = false;

function handle_request(): void
{
    global $slug_matches;
    global $is_404;

    $request = rtrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');

    // /api/[api]
    if (1 == preg_match("/^\/api\/(.*)$/", $request, $slug_matches)) {
        $api = $slug_matches[1];
        require __DIR__ . "/../api/{$api}.php";
        return;
    }

    if ($request == '/auth') {
        require __DIR__ . '/../views/auth.php';
        return;
    }

    if (!isset($_SESSION['username']) || !isset($_COOKIE['login'])) {
        require __DIR__ . '/../api/auth.php';
        return;
    }

    switch ($request) {
        case '':
        case '/index.php':
            header("Location: /home");
            break;

        case '/home':
            require __DIR__ . '/../views/home.php';
            break;

        case '/explore':
            require __DIR__ . '/../views/explore.php';
            break;

        case '/search':
            require __DIR__ . '/../views/search.php';
            break;

        case '/settings/profile':
            require __DIR__ . '/../views/profile_settings.php';
            break;

        // /profile/[username]
        case 1 == preg_match("/^\/profile\/([a-z0-9_]{5,15})$/", $request, $slug_matches):
            require __DIR__ . '/../views/profile.php';
            break;

        // /profile/[username]/following
        // /profile/[username]/followers
        case 1 == preg_match("/^\/profile\/([a-z0-9_]{5,15})\/(following|followers)$/", $request, $slug_matches):
            require __DIR__ . '/../views/connection.php';
            break;

        default:
            $is_404 = true;
            break;
    }
}

handle_request();

if ($is_404) {
    http_response_code(404);
    require __DIR__ . '/../views/404.php';
}