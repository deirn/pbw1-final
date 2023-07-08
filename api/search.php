<?php

use Database\Controllers\Connection;
use Database\Controllers\User;

$client_username = $_SESSION['username'] or not_found();
$query = $_GET['query'];

$users = $query[0] == '@'
    ? User::search_username(substr($query, 1))
    : User::search($query);

$result = [];

foreach ($users as $user) {
    $result[] = [
        'username' => $user->username,
        'display_name' => $user->display_name,
        'avatar' => $user->avatar,
        'bio' => $user->bio,
        'following_client' => Connection::is_present($user->username, $client_username),
        'followed_by_client' => Connection::is_present($client_username, $user->username)
    ];
}

header('Content-Type: application/json');
echo json_encode($result);
