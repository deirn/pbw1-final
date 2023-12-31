<?php

use Database\Controllers\User;

global $page_title;

$client_username = $_SESSION['username'];
$client_user = User::get_by_username($client_username) or not_found();

$page_title = "Home";
?>

<!doctype html>
<html lang="en">

<head>
    <?php PhpComponents::header(); ?>
    <?php CssComponents::navbar(); ?>
    <?php CssComponents::status(); ?>
</head>

<body>

<div class="c-container container p-0 d-flex">
    <?php PhpComponents::navbar(); ?>

  <div class="flex-grow-1 d-flex flex-column border-start border-end">
    <div class="flex-grow-1" id="main">
      <div class="p-3 d-flex gap-3 border-bottom" id="status-input-container">
        <div class="c-status-avatar flex-shrink-0 mb-auto">
          <img src="/assets/media/avatar/<?= $client_user->avatar ?>" alt="">
        </div>

        <div class="d-flex flex-column flex-grow-1 gap-2">
          <div>
          <textarea name="status-input" id="status-input" rows="3" maxlength="280"
                    aria-label="New status" placeholder="What's Happening???"
                    class="form-control form-control-lg"></textarea>
          </div>

          <div class="d-flex gap-3">
            <div class="flex-grow-1"></div>
            <div class="my-auto"><span id="status-input-counter">0</span>/280</div>
            <button class="btn btn-primary fw-bold" id="post-status" disabled>Post</button>
          </div>
        </div>
      </div>

      <div class="d-flex flex-column" id="status-container"></div>
    </div>

      <?php PhpComponents::navbar_mobile(); ?>
  </div>
</div>

<?php PhpComponents::footer(); ?>
<?php JsComponents::status(); ?>

<script>
    const statusInput = $("#status-input");
    const statusInputContainer = $("#status-input-container");
    const statusInputCounter = $("#status-input-counter");
    const postStatusButton = $("#post-status");
    const newStatusAnchor = $("#new-status-anchor");

    function fetchStatus(idBefore) {
        $.get("/api/get_home_status", {
            id_before: idBefore
        }, statusResponseHandler);
    }

    fetchStatus(0);

    statusInput.keyup(function () {
        const statusLength = statusInput.val().trim().length;
        statusInputCounter.html(statusLength);
        postStatusButton.prop("disabled", statusLength <= 0);
    });

    postStatusButton.click(function () {
        $.post("/api/post_status", {
            content: statusInput.val().trim()
        }, function (data) {
            const {status_id, status_content} = data;
            const statusDiv = createStatusDiv(data);
            statusContainer.prepend(statusDiv);
            setupStatusDiv(status_id, status_content);
            statusInput.val("");
            statusInput.keyup();
        });
    });

    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() === $(document).height()) {
            fetchStatus(earliestStatusId)
        }
    });

    newStatusAnchor.click(function () {
        setTimeout(function () {
            window.scrollTo(0, 0);
            statusInput.focus();
        }, 100)
    });

    if (window.location.hash === "#new") {
        newStatusAnchor.click();
    }
</script>
</body>
</html>
