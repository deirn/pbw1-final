<?php
global $component_args;

use Controllers\Database\Connection;
use Controllers\Database\User;

$client_username = $_SESSION['username'];
$arg_user = get_typed_arg(0, User::class);

$following_client = Connection::get($arg_user->username, $client_username) != null;
?>


<div>
  <span class="font-monospace text-body-secondary">@<?= $arg_user->username ?></span>
    <?php if ($following_client) { ?>
      <span class="badge text-secondary bg-body-secondary">Follows you</span>
    <?php } ?>
</div>
