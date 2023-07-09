<?php
global $component_args;

use Database\Controllers\User;

$arg_user = get_typed_arg(0, User::class);
?>

<div class="px-3 py-2 d-flex gap-3">
  <a class="c-back-button btn my-auto" href="javascript:history.back()">
    <i class="fa-solid fa-fw fa-arrow-left"></i>
  </a>
  <div class="fs-5"><?= $arg_user->display_name ?></div>
</div>