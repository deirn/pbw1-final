<?php

namespace Database\Controllers;

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

    public static function is_present(string $follower_username, string $following_username): bool
    {
        // language=mariadb
        $query = "select count(*) from connection where follower_username=? and following_username=?";
        $statement = DB::prepare_statement($query, "ss", $follower_username, $following_username);
        $statement->execute();
        $result = $statement->get_result();

        return $result->fetch_row()[0] > 0;
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
