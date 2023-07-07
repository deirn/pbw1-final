<?php /** @noinspection PhpUnreachableStatementInspection */

die('Already generated');

require_once __DIR__ . '/../../index.php';
require_once __DIR__ . '/output/GeneratedUsers.php';

use Database\Controllers\DB;
use Database\Datagen\Output\GeneratedUsers;
use Faker\Factory;

$faker = Factory::create();
$output_file = fopen(__DIR__ . '/output/GeneratedStatusAncestors.php', 'w') or die('Unable to open file');

// language=php
$header = <<<EOL
<?php

namespace Database\\Datagen\\Output;

class GeneratedStatusAncestors {
    public static array \$output = [
EOL;

// language=php
$footer = <<<EOL

    ];   
}
EOL;

fwrite($output_file, $header);

foreach (GeneratedUsers::$output as $index => $user) {
    $username = $user['username'];
    $total_status = rand(5, 10);
    echo "{$username}: {$total_status}" . PHP_EOL;

    for ($i = 0; $i < $total_status; $i++) {
        $status_content = $faker->text(280);
        $created_at = DB::create_date_time_string($faker->dateTimeBetween('-2 month'));

        // language=php
        $entry = <<<EOL

        [
            'username' => '{$username}',
            'status_content' => '{$status_content}',
            'created_at' => '{$created_at}'
        ],
EOL;
        fwrite($output_file, $entry);
    }
}

fwrite($output_file, $footer);
fclose($output_file);
