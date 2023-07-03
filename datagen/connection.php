<?php /** @noinspection PhpUnreachableStatementInspection */

die('Already generated');

require_once __DIR__ . '/../index.php';
require_once __DIR__ . '/output/user.php';

use Datagen\Output\GeneratedUsers;

$output_file = fopen(__DIR__ . '/output/connection.php', 'w') or die('Unable to open file');

// language=php
$header = <<<EOL
<?php

namespace Datagen\\Output;

class GeneratedConnections {
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
    $total_following = rand(5, 30);
    $followed = [];

    echo "total: {$total_following}" . PHP_EOL;

    for ($j = 1; $j <= $total_following; $j++) {
        while (true) {
            $target_index = rand(0, count(GeneratedUsers::$output) - 1);
            if ($target_index == $index) continue;

            $target_user = GeneratedUsers::$output[$target_index];
            $target_username = $target_user['username'];
            if (isset($followed[$target_username])) continue;

            echo "{$j}. {$username} => {$target_username}" . PHP_EOL;

            // language=php
            $entry = <<<EOL

        [
            'follower_username' => '{$username}',
            'following_username' => '{$target_username}'
        ],   
EOL;
            fwrite($output_file, $entry);

            $followed[$target_username] = true;
            break;
        }
    }

    echo PHP_EOL;
}

fwrite($output_file, $footer);
fclose($output_file);
