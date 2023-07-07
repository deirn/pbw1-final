<?php /** @noinspection PhpUnreachableStatementInspection */

die('Already generated');

require_once __DIR__ . '/../../index.php';

use Database\Controllers\DB;
use Database\Datagen\Output\GeneratedStatusAncestors;
use Database\Datagen\Output\GeneratedUsers;
use Faker\Factory;

$faker = Factory::create();
$output_file = fopen(__DIR__ . '/output/GeneratedStatusReplies.php', 'w') or die('Unable to open file');

// language=php
$header = <<<EOL
<?php

namespace Database\\Datagen\\Output;

class GeneratedStatusReplies {
    public static array \$output = [
EOL;

// language=php
$footer = <<<EOL

    ];   
}
EOL;

fwrite($output_file, $header);

$parent_status_id = 0;
foreach (GeneratedStatusAncestors::$output as $ancestor) {
    $parent_status_id++;
    $total_reply = rand(1, 5);
    $ancestor_time = DB::parse_date_time_string($ancestor['created_at']);

    for ($i = 0; $i < $total_reply; $i++) {
        $user_index = rand(0, count(GeneratedUsers::$output) - 1);
        $username = GeneratedUsers::$output[$user_index]['username'];

        $status_content = $faker->text(280);

        $reply_after = rand(1, 100);
        $reply_time = $ancestor_time->add(new DateInterval("PT{$reply_after}H"));
        $created_at = DB::create_date_time_string($reply_time);

        // language=php
        $entry = <<<EOL

        [
            'username' => '{$username}',
            'parent_status_id' => {$parent_status_id},
            'status_content' => '{$status_content}',
            'created_at' => '{$created_at}'
        ],
EOL;
        fwrite($output_file, $entry);
    }
}

fwrite($output_file, $footer);
fclose($output_file);
