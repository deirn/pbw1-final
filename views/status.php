<?php

use Database\Controllers\Engagement;
use Database\Controllers\Status;

global $page_title;
global $slug_matches;

$status_id = $slug_matches[1];
$status = Status::get($status_id);
$liked_by_client = Engagement::is_status_liked($_SESSION['username'], $status_id);

$page_title = "{$status->display_name}: \"{$status->status_content}\"";
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

  <div class="flex-grow-1 border-end" id="main">
    <div class="d-flex flex-column" id="ancestor-status-container"></div>

    <div class="px-3 d-flex flex-column gap-3" id="main-status">
      <div class="d-flex gap-3">
        <div class="d-flex flex-column flex-shrink-0">
          <div class="c-thread-line c-hidden" id="thread-line-before"></div>
          <div class="c-status-avatar"></div>
        </div>

        <div class="pt-3 d-flex flex-column flex-grow-1 gap-2">
          <div>
            <div>
              <a href="/profile/<?= $status->username ?>"
                 class="link-body-emphasis link-underline link-underline-opacity-0 link-underline-opacity-100-hover fw-bold">
                  <?= $status->display_name ?>
              </a>
              <span class="float-end font-monospace text-body-tertiary">#<?= $status->status_id ?></span>
            </div>


              <?php PhpComponents::profile_username($status->username) ?>

          </div>

        </div>
      </div>

      <div class="text-break fs-5"><?= $status->status_content ?></div>

      <div class="text-break pb-3 border-bottom d-flex gap-4">
        <div class="my-auto text-body-tertiary" id="main-status-date"></div>
        <div class="my-auto"><span class="fw-bold" id="main-status-like-counter"><?= $status->like_count ?></span> Likes
        </div>
        <button class="c-status-like btn">
          <i class="<?= $liked_by_client ? 'fa-solid' : 'fa-regular' ?> fa-fw fa-heart" id="main-status-heart"></i>
        </button>
      </div>
    </div>

    <div class="p-3 d-flex gap-3 border-bottom">
      <div class="c-status-avatar flex-shrink-0 mb-auto"></div>

      <div class="d-flex flex-column flex-grow-1 gap-2">
        <div>
          <textarea name="status-input" id="reply-input" rows="3" maxlength="280"
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
    $("#main-status-date").html(formatStatusDate(parseMysqlDateTime("<?= $status->created_at ?>")))

    const main = $("#main");
    const mainStatus = $("#main-status");
    const mainStatusLikeCounter = $("#main-status-like-counter");
    const mainStatusHeart = $("#main-status-heart");
    const ancestorStatusContainer = $("#ancestor-status-container");

    const replyInput = $("#reply-input");
    const replyInputCounter = $("#reply-input-counter");
    const postReplyButton = $("#post-reply");

    replyInput.keyup(function () {
        const replyLength = replyInput.val().trim().length;
        replyInputCounter.html(replyLength);
        postReplyButton.prop("disabled", replyLength <= 0);
    });

    postReplyButton.click(function () {
        $.post("/api/post_reply", {
            parent_status_id: <?= $status_id ?>,
            content: replyInput.val().trim()
        }, function (json) {
            const data = JSON.parse(json);
            const {status_id} = data;

            const statusDiv = createStatusDiv(data);
            statusContainer.prepend(statusDiv);
            setupStatusEventHandler(status_id);
            replyInput.val("");
            replyInput.keyup();

            if (earliestStatusId === 0) {
                earliestStatusId = status_id;
            }
        });
    });

    $.get("/api/get_status_ancestor", {
        status_id: <?= $status_id ?>
    }, function (json) {
        const data = JSON.parse(json);
        const oldHeight = main.height();
        let ancestorHeight = 0;

        for (let i = 0; i < data.length; i++) {
            const {status_id} = data[i];
            const statusDiv = createStatusDiv(data[i]);

            ancestorStatusContainer.prepend(statusDiv);
            setupStatusEventHandler(status_id);

            ancestorHeight += $(`#status-${status_id}`).outerHeight();

            if (i < (data.length - 1)) {
                $(`#status-${status_id} #thread-line-before`).removeClass("c-hidden");
            }

            $(`#status-${status_id} #thread-line-after`).removeClass("c-hidden");
        }

        if (data.length > 0) {
            mainStatus.find("#thread-line-before").removeClass("c-hidden");
        }

        main.css("min-height", (oldHeight + ancestorHeight) + "px");
        mainStatus.get(0).scrollIntoView();
    });

    mainStatus.find(".c-status-like").click(function () {
        $.post("/api/like", {
            status_id: <?= $status_id ?>
        }, function (json) {
            const data = JSON.parse(json);

            const {
                liked,
                new_like_count
            } = data;

            if (new_like_count !== undefined) {
                mainStatusLikeCounter.html(new_like_count);
            }

            if (liked) mainStatusHeart
                .removeClass("fa-regular")
                .addClass("fa-solid");
            else mainStatusHeart
                .removeClass("fa-solid")
                .addClass("fa-regular")
        });
    });

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
