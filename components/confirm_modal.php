<?php
global $component_args;

$arg_id = $component_args[0];
$arg_title = $component_args[1];
$arg_body = $component_args[2];

?>

<div class="modal fade" id="<?= $arg_id ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel"><?= $arg_title ?></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <?= $arg_body ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="confirm-button" data-bs-dismiss="modal">Understood</button>
      </div>
    </div>
  </div>
</div>
