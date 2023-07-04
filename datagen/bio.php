<?php /** @noinspection PhpUnreachableStatementInspection */

//die('Already generated');

require_once __DIR__ . '/../index.php';
require_once __DIR__ . '/output/user.php';

use Datagen\Output\GeneratedUsers;
use Faker\Factory;

$faker = Factory::create();
$output_file = fopen(__DIR__ . '/output/bio.php', 'w') or die('Unable to open file');

// language=php
$header = <<<EOL
<?php

namespace Datagen\\Output;

class GeneratedBios {
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
    $bio = addslashes($faker->realText(160));

    // language=php
    $entry = <<<EOL

        '{$username}' => '{$bio}',
EOL;
    fwrite($output_file, $entry);
}

fwrite($output_file, $footer);
fclose($output_file);
