<?php

namespace Database\Controllers;

class Engagement
{
    public int $engagement_id;
    public string $username;
    public int $status_id;

    public static function get(string $username, int $status_id): ?Engagement
    {
        // language=mariadb
        $query = "select * from engagement where username=? and status_id=?";
        $statement = DB::prepare_statement($query, "si", $username, $status_id);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_object(Engagement::class);
        }

        return null;
    }

    public static function create(string $username, int $status_id): ?Engagement
    {
        // language=mariadb
        $query = "insert into engagement(username, status_id) values (?, ?)";
        $statement = DB::prepare_statement($query, "si", $username, $status_id);

        if ($statement->execute()) {
            $engagement = new Engagement();
            $engagement->engagement_id = $statement->insert_id;
            $engagement->username = $username;
            $engagement->status_id = $status_id;
            return $engagement;
        }

        return null;
    }

    public static function is_status_liked(string $username, int $status_id): bool
    {
        // language=mariadb
        $query = "select count(*) from engagement where username=? and status_id=?";
        $statement = DB::prepare_statement($query, "si", $username, $status_id);
        $statement->execute();
        $result = $statement->get_result();

        return $result->fetch_row()[0] > 0;
    }

    public static function count(string $status_id): int
    {
        // language=mariadb
        $query = "select count(*) from engagement where status_id=?";
        $statement = DB::prepare_statement($query, "i", $status_id);
        $statement->execute();
        $result = $statement->get_result();

        return $result->fetch_row()[0];
    }

    public function delete(): void
    {
        // language=mariadb
        $query = "delete from engagement where engagement_id=?";
        $statement = DB::prepare_statement($query, "i", $this->engagement_id);
        $statement->execute();
    }
}