<?php

use JetBrains\PhpStorm\NoReturn;

require_once __DIR__ . '/../index.php';
require_once __DIR__ . '/../components/index.php';

$request_uri = rtrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');

$page_title = '';
$slug_matches = [];

function handle_request(): void
{
    global $request_uri;
    global $slug_matches;

    // /api/[api]
    if (1 == preg_match("/^\/api\/(.*)$/", $request_uri, $slug_matches)) {
        $api = $slug_matches[1];
        require __DIR__ . "/../api/{$api}.php";
        return;
    }

    if ($request_uri == '/auth') {
        require __DIR__ . '/../views/auth.php';
        return;
    }

    if (!isset($_SESSION['username']) || !isset($_COOKIE['login'])) {
        require __DIR__ . '/../api/auth.php';
        return;
    }

    switch ($request_uri) {
        case '':
        case '/index.php':
            header("Location: /home");
            break;

        case '/home':
            require __DIR__ . '/../views/home.php';
            break;

        case '/search':
            require __DIR__ . '/../views/search.php';
            break;

        case '/settings/profile':
            require __DIR__ . '/../views/profile_settings.php';
            break;

        // /profile/[username]
        case 1 == preg_match("/^\/profile\/([a-z0-9_]{5,15})$/", $request_uri, $slug_matches):
            require __DIR__ . '/../views/profile.php';
            break;

        // /profile/[username]/following
        // /profile/[username]/followers
        case 1 == preg_match("/^\/profile\/([a-z0-9_]{5,15})\/(following|followers)$/", $request_uri, $slug_matches):
            require __DIR__ . '/../views/connection.php';
            break;

        // /status/[status_id]
        case 1 == preg_match("/^\/status\/([0-9]+)$/", $request_uri, $slug_matches):
            require __DIR__ . '/../views/status.php';
            break;

        default:
            not_found();
    }
}

#[NoReturn]
function not_found(): void
{
    http_response_code(404);
    require __DIR__ . '/../views/404.php';
    die();
}

handle_request();
