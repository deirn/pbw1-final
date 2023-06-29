<?php

namespace Controllers\Database;

use mysqli;
use mysqli_stmt;

class DB
{
    private static ?mysqli $connection = null;

    public static function connection(): mysqli
    {
        if (self::$connection == null) {
            $host = $_ENV['DB_HOST'];
            $port = $_ENV['DB_PORT'];
            $username = $_ENV['DB_USERNAME'];
            $password = $_ENV['DB_PASSWORD'];
            $db_name = $_ENV['DB_NAME'];

            self::$connection = new mysqli($host, $username, $password, $db_name, $port);

            if (self::$connection->connect_error) {
                die("Database connection error: " . self::$connection->connect_error);
            }
        }

        return self::$connection;
    }

    public static function prepare_statement(string $query, ?string $format = null, ...$args): mysqli_stmt
    {
        $statement = self::connection()->prepare($query);

        if ($format != null) {
            $statement->bind_param($format, ...$args);
        }

        return $statement;
    }

    // https://stackoverflow.com/questions/614671/commands-out-of-sync-you-cant-run-this-command-now
    public static function clear_stored_result(): void
    {
        $connection = self::connection();

        do {
            if ($res = $connection->store_result()) {
                $res->free();
            }
        } while ($connection->more_results() && $connection->next_result());
    }
}

class User
{
    public string $username;
    public string $password;
    public string $display_name;
    public ?string $avatar;

    public static function get(string $username): ?User
    {
        // language=MySQL
        $query = "SELECT * FROM user where username=?";
        $statement = DB::prepare_statement($query, "s", $username);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_object(User::class);
        }

        return null;
    }

    public static function create(string $username, string $password, string $display_name): ?User
    {
        // language=MySQL
        $query = "INSERT INTO user(username, password, display_name) values (?, ?, ?)";
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $statement = DB::prepare_statement($query, "sss", $username, $hashed_password, $display_name);

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

    public static function is_valid_username_characters(string $username): bool
    {
        return preg_match("/^[a-z0-9_]*$/", $username) === 1;
    }

    public static function is_valid_username_length(string $username): bool
    {
        return strlen($username) >= 5 && strlen($username) <= 15;
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
        $statement = DB::prepare_statement($query, "i", $status_id);
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
