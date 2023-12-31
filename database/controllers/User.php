<?php

namespace Database\Controllers;

class User
{
    public int $user_id;
    public string $username;
    public string $password;
    public string $display_name;
    public ?string $avatar;
    public ?string $bio;

    public function html_display_name(): string
    {
        return htmlspecialchars($this->display_name);
    }

    public function html_bio(): string
    {
        return $this->bio == null ? '' : htmlspecialchars($this->bio);
    }

    public static function get_by_id(int $user_id): ?User
    {
        // language=mariadb
        $query = "select * from user where user_id=?";
        $statement = DB::prepare_statement($query, "i", $user_id);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_object(User::class);
        }

        return null;
    }

    public static function get_by_username(string $username): ?User
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

    /**
     * @return User[]
     */
    public static function search(string $query_string): iterable
    {

        // language=mariadb
        $query = "select * from user
                  where username like ?
                     or display_name like ?
                  limit 10";
        $query_string = "%{$query_string}%";
        $statement = DB::prepare_statement($query, "ss", $query_string, $query_string);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            while ($res = $result->fetch_object(User::class)) {
                yield $res;
            }
        }
    }

    /**
     * @return User[]
     */
    public static function search_username(string $query_username): iterable
    {
        // language=mariadb
        $query = "select * from user
                  where username like ?
                  limit 10";
        $query_username = "%{$query_username}%";
        $statement = DB::prepare_statement($query, "s", $query_username);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            while ($res = $result->fetch_object(User::class)) {
                yield $res;
            }
        }
    }

    public static function create(string $username, string $password, string $display_name, ?string $bio = null, ?string $avatar = null): ?User
    {
        // language=mariadb
        $query = "insert into user(username, password, display_name, bio, avatar) values (?, ?, ?, ?, ?)";
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $statement = DB::prepare_statement($query, "sssss", $username, $hashed_password, $display_name, $bio, $avatar);

        if ($statement->execute()) {
            $user = new User();
            $user->username = $username;
            $user->password = $password;
            $user->display_name = $display_name;
            $user->avatar = $avatar;
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