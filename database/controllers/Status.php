<?php

namespace Database\Controllers;

class Status
{
    public int $status_id;
    public string $username;
    public ?int $parent_status_id;
    public string $status_content;
    public string $created_at;
    public ?string $updated_at;

    // table: user
    public ?string $display_name;

    // table: engagement
    public ?int $like_count;

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

    /**
     * @return Status[]
     */
    public static function get_status_from_user(string $status_username, int $id_before): iterable
    {
        if ($id_before == 0) $id_before = PHP_INT_MAX;

        // language=mariadb
        $query = "select status.*,
                         user.display_name,
                         count(engagement.status_id) as like_count
                  from status
                       join user on user.username=status.username
                       left join engagement on status.status_id=engagement.status_id
                  where status.username=?
                    and status.status_id<?
                  group by status.status_id
                  order by status.status_id desc
                  limit 25";
        $statement = DB::prepare_statement($query, "si", $status_username, $id_before);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            while ($res = $result->fetch_object(Status::class)) {
                yield $res;
            }
        }
    }

    /**
     * @return Status[]
     */
    public static function get_status_from_followed(string $username, int $id_before): iterable
    {
        if ($id_before == 0) $id_before = PHP_INT_MAX;

        // language=mariadb
        $query = "select status.*,
                         user.display_name,
                         count(engagement.status_id) as like_count
                  from status
                       join user
                       join connection 
                            on connection.follower_username=?
                           and connection.following_username=user.username
                           and connection.following_username=status.username
                       left join engagement 
                            on status.status_id=engagement.status_id
                  where status.status_id<?
                  group by status.status_id
                  order by status.status_id desc
                  limit 25";

        $statement = DB::prepare_statement($query, "si", $username, $id_before);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            while ($res = $result->fetch_object(Status::class)) {
                yield $res;
            }
        }
    }
}