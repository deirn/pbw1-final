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
    public ?string $bio;

    public static function get(string $username): ?User
    {
        // language=mariadb
        $query = "select * from user where username=?";
        $statement = DB::prepare_statement($query, "s", $username);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_object(User::class);
        }

        return null;
    }

    public static function create(string $username, string $password, string $display_name, ?string $bio = null): ?User
    {
        // language=mariadb
        $query = "insert into user(username, password, display_name, bio) values (?, ?, ?, ?)";
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $statement = DB::prepare_statement($query, "ssss", $username, $hashed_password, $display_name, $bio);

        if ($statement->execute()) {
            $user = new User();
            $user->username = $username;
            $user->password = $password;
            $user->display_name = $display_name;
            $user->avatar = null;
            $user->bio = $bio;
            return $user;
        }

        return null;
    }

    public static function edit(string $username, string $display_name, ?string $bio): bool
    {
        // language=mariadb
        $query = "update user set display_name=?, bio=? where username=?";
        $statement = DB::prepare_statement($query, "sss", $display_name, $bio, $username);
        return $statement->execute();
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
    public string $created_at;
    public ?string $updated_at;

    public static function get_status(int $status_id): ?Status
    {
        // language=mariadb
        $query = "select * from status where status_id=?";
        $statement = DB::prepare_statement($query, "i", $status_id);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_object(Status::class);
        }

        return null;
    }

    public static function create_ancestor(string $username, string $status_content, string $created_at): ?Status
    {
        // language=mariadb
        $query = "insert into status(username, status_content, created_at) values (?, ?, ?)";
        $statement = DB::prepare_statement($query, "sss", $username, $status_content, $created_at);

        if ($statement->execute()) {
            $status = new Status();
            $status->status_id = $statement->insert_id;
            $status->parent_status_id = null;
            $status->status_content = $status_content;
            $status->created_at = $created_at;
            $status->updated_at = null;
            return $status;
        }

        return null;
    }
}

class Connection
{
    public readonly int $connection_id;
    public readonly string $follower_username;
    public readonly string $following_username;

    private ?User $_follower = null;
    private ?User $_following = null;

    public static function get(string $follower_username, string $following_username): ?Connection
    {
        // language=mariadb
        $query = "select * from connection where follower_username=? and following_username=?";
        $statement = DB::prepare_statement($query, "ss", $follower_username, $following_username);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_object(Connection::class);
        }

        return null;
    }

    public static function create(string $follower_username, string $following_username): ?Connection
    {
        // language=mariadb
        $query = "insert into connection(follower_username, following_username) values (?, ?)";
        $statement = DB::prepare_statement($query, "ss", $follower_username, $following_username);

        if ($statement->execute()) {
            $connection = new Connection();
            $connection->connection_id = $statement->insert_id;
            $connection->follower_username = $follower_username;
            $connection->following_username = $following_username;
            return $connection;
        }

        return null;
    }

    /**
     * @return iterable(Connection)
     */
    public static function get_followers(string $username): iterable
    {
        // language=mariadb
        $query = "select * from connection where following_username=? order by connection_id desc";
        $statement = DB::prepare_statement($query, "s", $username);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            while ($res = $result->fetch_object(Connection::class)) {
                yield $res;
            }
        }
    }

    public static function get_followers_count(string $username): int
    {
        // language=mariadb
        $query = "select count(*) from connection where following_username=?";
        $statement = DB::prepare_statement($query, "s", $username);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_row()[0];
        }

        return 0;
    }

    /**
     * @return iterable(Connection)
     */
    public static function get_following(string $username): iterable
    {
        // language=mariadb
        $query = "select * from connection where follower_username=?";
        $statement = DB::prepare_statement($query, "s", $username);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            while ($res = $result->fetch_object(Connection::class)) {
                yield $res;
            }
        }
    }

    public static function get_following_count(string $username): int
    {
        // language=mariadb
        $query = "select count(*) from connection where follower_username=?";
        $statement = DB::prepare_statement($query, "s", $username);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_row()[0];
        }

        return 0;
    }

    public function delete(): void
    {
        // language=mariadb
        $query = "delete from connection where connection_id=?";
        $statement = DB::prepare_statement($query, "s", $this->connection_id);
        $statement->execute();
    }

    public function resolve_follower(): User
    {
        if ($this->_follower == null) {
            $this->_follower = User::get($this->follower_username);
        }

        return $this->_follower;
    }

    public function resolve_following(): User
    {
        if ($this->_following == null) {
            $this->_following = User::get($this->following_username);
        }

        return $this->_following;
    }
}
