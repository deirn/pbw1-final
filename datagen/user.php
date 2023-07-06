<?php /** @noinspection PhpUnreachableStatementInspection */

die('Already generated');

require_once __DIR__ . '/../index.php';

use Controllers\Database\User;
use Faker\Factory;

$faker = Factory::create();
$output_file = fopen(__DIR__ . '/output/user.php', 'w') or die('Unable to open file');

// language=php
$header = <<<EOL
<?php

namespace Datagen\\Output;

class GeneratedUsers {
    public static array \$output = [
EOL;

// language=php
$footer = <<<EOL

    ];   
}
EOL;

fwrite($output_file, $header);

for ($i = 0; $i < 50; $i++) {
    $username_validator = function ($username) {
        return User::is_valid_username_characters($username) && User::is_valid_username_length($username);
    };

    $username = addslashes($faker->valid($username_validator)->userName());
    $password = addslashes($faker->bothify("??#??-?#?"));
    $display_name = addslashes($faker->name());

    // language=php
    $entry = <<<EOL

        [
            'username' => '{$username}',
            'password' => '{$password}',
            'display_name' => '{$display_name}'
        ],
EOL;
    fwrite($output_file, $entry);
}

fwrite($output_file, $footer);
fclose($output_file);
