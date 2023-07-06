<?php
global $component_args;

use Database\Controllers\User;

$arg_user = get_typed_arg(0, User::class);
?>

<div class="px-3 py-2 d-flex gap-3">
  <button class="c-back-button btn my-auto" onclick="history.back()"><i class="fa-solid fa-fw fa-arrow-left"></i>
  </button>
  <div class="fs-5"><?= $arg_user->display_name ?></div>
</div>