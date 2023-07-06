<?php /** @noinspection PhpUnreachableStatementInspection */

//die('Already generated');

require_once __DIR__ . '/../index.php';
require_once __DIR__ . '/output/user.php';
require_once __DIR__ . '/output/status_ancestor.php';

use Datagen\Output\GeneratedStatusAncestors;
use Datagen\Output\GeneratedUsers;

$output_file = fopen(__DIR__ . '/output/engagement.php', 'w') or die('Unable to open file');

// language=php
$header = <<<EOL
<?php

namespace Datagen\\Output;

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
foreach (GeneratedStatusAncestors::$output as $status) {
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
