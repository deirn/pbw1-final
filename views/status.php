<?php

use Database\Controllers\Status;

global $page_title;
global $slug_matches;

$status_id = $slug_matches[1];
$status = Status::get($status_id);

$page_title = "???";
?>

<!doctype html>
<html lang="en">

<head>
    <?php PhpComponents::header(); ?>
    <?php CssComponents::navbar(); ?>
    <?php CssComponents::status(); ?>
</head>

<body>

<div class="c-container container d-flex">
    <?php PhpComponents::navbar(); ?>

  <div class="flex-grow-1 border-end">
    <div class="p-3 d-flex flex-column gap-3 border-bottom">
      <div class="d-flex gap-3">
        <div class="c-status-avatar flex-shrink-0 mb-auto"></div>

        <div class="d-flex flex-column flex-grow-1 gap-2">
          <div>
            <div class="fw-bold"><?= $status->display_name ?></div>

              <?php PhpComponents::profile_username($status->username) ?>

          </div>

        </div>
      </div>

      <div class="text-break fs-5"><?= $status->status_content ?></div>
    </div>

    <div class="p-3 d-flex gap-3 border-bottom">
      <div class="c-status-avatar flex-shrink-0 mb-auto"></div>

      <div class="d-flex flex-column flex-grow-1 gap-2">
        <div>
          <textarea name="status-input" id="status-input" rows="3" maxlength="280"
                    aria-label="New status" placeholder="Post your reply!"
                    class="form-control form-control-lg"></textarea>
        </div>

        <div class="d-flex gap-3">
          <div class="flex-grow-1"></div>
          <div class="my-auto"><span id="status-input-counter">0</span>/280</div>
          <button class="btn btn-primary fw-bold" id="post-reply" disabled>Reply</button>
        </div>
      </div>
    </div>

    <div class="d-flex flex-column" id="status-container"></div>

  </div>
</div>

<?php PhpComponents::footer(); ?>
<?php JsComponents::status(); ?>

<script>
    function fetchReply(idBefore) {
        $.get("/api/get_reply", {
            parent_status_id: <?= $status_id ?>,
            id_before: idBefore
        }, statusResponseHandler);
    }

    fetchReply(0);

    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() === $(document).height()) {
            fetchReply(earliestStatusId)
        }
    });
</script>
</body>
</html>
