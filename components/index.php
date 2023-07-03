<?php

use Controllers\Database\User;

$component_args = [];

/**
 * @template T
 * @param class-string<T> $type
 * @return T|null
 * @noinspection PhpUnusedParameterInspection
 */
function get_typed_arg(int $index, string $type = stdClass::class): object|null
{
    global $component_args;
    return $component_args[$index];
}

/**
 * @method static header
 * @method static footer
 * @method static navbar
 * @method static profile_header(User $user)
 */
class PhpComponents
{
    public static function __callStatic(string $name, $args)
    {
        global $component_args;
        $component_args = $args;
        require __DIR__ . "/{$name}.php";
    }
}

/**
 * @method static navbar
 * @method static root
 * @method static profile_header
 */
class CssComponents
{
    public static function __callStatic(string $name, $args)
    {
        ?>
      <link rel="stylesheet" href="/assets/css/<?= $name ?>.css">
        <?php
    }
}

/**
 * @method static tooltip
 */
class JsComponents
{
    public static function __callStatic(string $name, $args)
    {
        ?>
      <script src="/assets/js/<?= $name ?>.js"></script>
        <?php
    }
}
