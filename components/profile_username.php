<?php
global $component_args;

use Database\Controllers\Connection;

$client_username = $_SESSION['username'];
$arg_username = $component_args[0];

$following_client = Connection::get($arg_username, $client_username) != null;
?>


<div>
  <span class="font-monospace text-body-secondary">@<?= $arg_username ?></span>
    <?php if ($following_client) { ?>
      <span class="badge text-secondary bg-body-secondary">Follows you</span>
    <?php } ?>
</div>
