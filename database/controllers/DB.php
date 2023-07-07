<?php

namespace Database\Controllers;

use DateTime;
use DateTimeInterface;
use mysqli;
use mysqli_stmt;

class DB
{
    public const DATE_FORMAT = 'Y-m-d H:i:s';

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

    public static function create_date_time_string(DateTimeInterface $date_time = new DateTime()): string
    {
        return $date_time->format(self::DATE_FORMAT);
    }
}