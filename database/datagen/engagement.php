<?php /** @noinspection PhpUnreachableStatementInspection */

die('Already generated');

require_once __DIR__ . '/../../index.php';

use Database\Datagen\Output\GeneratedStatusAncestors;
use Database\Datagen\Output\GeneratedStatusReplies;
use Database\Datagen\Output\GeneratedUsers;

$output_file = fopen(__DIR__ . '/output/GeneratedEngagements.php', 'w') or die('Unable to open file');

// language=php
$header = <<<EOL
<?php

namespace Database\\Datagen\\Output;

class GeneratedEngagements {
    public static array \$output = [
EOL;

// language=php
$footer = <<<EOL

    ];   
}
EOL;

fwrite($output_file, $header);

$status_id = 0;
foreach ([...GeneratedStatusAncestors::$output, ...GeneratedStatusReplies::$output] as $status) {
    $status_id++;
    $total_engagement = rand(0, 5);
    $liking_username = [];

    for ($i = 0; $i < $total_engagement; $i++) {
        while (true) {
            $user_index = rand(0, count(GeneratedUsers::$output) - 1);
            $user = GeneratedUsers::$output[$user_index];
            $username = $user['username'];
            if (isset($liking_username[$username])) continue;

            // language=php
            $entry = <<<EOL

        [
            'username' => '{$username}',
            'status_id' => '{$status_id}'
        ],   
EOL;
            fwrite($output_file, $entry);

            $liking_username[$username] = true;
            break;
        }
    }
}

fwrite($output_file, $footer);
fclose($output_file);
