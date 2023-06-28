<?php

namespace Controllers\Database;

use mysqli;
use mysqli_stmt;

$_host = $_ENV['DB_HOST'];
$_port = $_ENV['DB_PORT'];
$_username = $_ENV['DB_USERNAME'];
$_password = $_ENV['DB_PASSWORD'];
$_db_name = $_ENV['DB_NAME'];

$_con = new mysqli($_host, $_username, $_password, $_db_name, $_port);

if ($_con->connect_error) {
    die("Database connection error: " . $_con->connect_error);
}

class User
{
    public string $username;
    public string $password;
    public string $display_name;
    public ?string $avatar;


    public static function get_user(string $username): ?User
    {
        // language=MySQL
        $query = "SELECT * FROM user where username=?";
        $statement = prepare_statement($query, "s", $username);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_object(User::class);
        }

        return null;
    }

    public static function create_new_user(string $username, string $password, string $display_name): ?User
    {
        // language=MySQL
        $query = "INSERT INTO user(username, password, display_name) values (?, ?, ?)";
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $statement = prepare_statement($query, "sss", $username, $hashed_password, $display_name);

        if ($statement->execute()) {
            $user = new User();
            $user->username = $username;
            $user->password = $password;
            $user->display_name = $display_name;
            $user->avatar = null;
            return $user;
        }

        return null;
    }
}

class Status
{
    public int $status_id;
    public string $username;
    public ?int $parent_status_id;
    public string $status_content;

    public static function get_status(int $status_id): ?Status
    {
        // language=MySQL
        $query = "SELECT * FROM status where status_id=?";
        $statement = prepare_statement($query, "i", $status_id);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_object(Status::class);
        }

        return null;
    }
}

class Connection
{
    public int $connection_id;
    public string $follower_username;
    public string $following_username;
}

function prepare_statement(string $query, string $format, ...$args): mysqli_stmt
{
    global $_con;

    $statement = $_con->prepare($query);
    $statement->bind_param($format, ...$args);
    return $statement;
}
