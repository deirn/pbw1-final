<?php

$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$db_name = $_ENV['DB_NAME'];

$connection = new mysqli($host, $username, $password, "", $port);

if ($connection->connect_error) {
    die("Database connection error: " . $connection->connect_error);
}

// language=mariadb
$query = "create or replace database `{$db_name}`";

if ($connection->query($query)) {
    echo "Created database " . $db_name . PHP_EOL;
} else {
    die("Error creating database: " . $connection->connect_error);
}

$connection->close();
