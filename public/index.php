<?php

use JetBrains\PhpStorm\NoReturn;

require_once __DIR__ . '/../index.php';
require_once __DIR__ . '/../components/index.php';

$request_uri = rtrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');

$page_title = '';
$slug_matches = [];
$error_code = 200;
$error_message = '';

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

    if (!isset($_SESSION['username']) || !isset($_COOKIE['login2'])) {
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
function error_page(int $code, string $message): void
{
    global $error_code;
    global $error_message;

    $error_code = $code;
    $error_message = $message;

    http_response_code($error_code);
    require __DIR__ . '/../views/__ERROR.php';
    die();
}

#[NoReturn]
function not_found(string $message = 'Where are you going?'): void
{
    error_page(404, $message);
}


#[NoReturn]
function server_error(): void
{
    error_page(500, 'Internal Server Error');
}

handle_request();
