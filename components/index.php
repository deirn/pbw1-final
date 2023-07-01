<?php

/**
 * @method static header
 * @method static footer
 * @method static navbar
 */
class PhpComponents
{
    public static function __callStatic(string $name, $args)
    {
        require __DIR__ . "/{$name}.php";
    }
}

/**
 * @method static navbar
 * @method static root
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
