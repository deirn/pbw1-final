<?php

use Database\Controllers\Engagement;
use Database\Controllers\Status;

global $page_title;
global $slug_matches;

$client_username = $_SESSION['username'];

$status_id = $slug_matches[1];
$status = Status::get($status_id) or not_found();
$status_by_client = !$status->deleted && $status->username == $client_username;
$liked_by_client = Engagement::is_status_liked($client_username, $status_id);

$page_title = $status->deleted ? 'Deleted Status' : "{$status->display_name}: \"{$status->status_content}\"";
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

      <?php if (!$status->deleted) { ?>
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

          <div class="text-break fs-5" id="main-status-content"><?= htmlspecialchars($status->status_content) ?></div>

            <?php if ($status_by_client) { ?>
              <div class="d-flex flex-column flex-grow-1 gap-2 pb-3 border-bottom d-none" id="edit-status-container">
                <div>
                  <textarea name="status-input" id="edit-input" rows="3" maxlength="280"
                            aria-label="New status" placeholder="Edit status"
                            class="form-control form-control-lg"></textarea>
                </div>

                <div class="d-flex gap-3">
                  <div class="flex-grow-1"></div>
                  <div class="my-auto"><span id="edit-input-counter">0</span>/280</div>
                  <button class="btn btn-light border border-dark-subtle fw-bold" id="post-edit-cancel">Cancel</button>
                  <button class="btn btn-primary fw-bold" id="post-edit-submit" disabled>Edit</button>
                </div>
              </div>
            <?php } // $status_by_client ?>

          <div class="text-break pb-3 border-bottom d-flex gap-4">
            <div class="my-auto"><span class="fw-bold" id="main-status-like-counter"><?= $status->like_count ?></span>
              Likes
            </div>
            <button class="c-status-button c-status-like btn">
              <i class="<?= $liked_by_client ? 'fa-solid' : 'fa-regular' ?> fa-fw fa-heart" id="main-status-heart"></i>
            </button>

            <div class="flex-grow-1"></div>
            <div class="my-auto text-body-tertiary" id="main-status-time"></div>

              <?php if ($status_by_client) { ?>

                <div class="d-flex gap-2">
                  <button class="c-status-button btn" id="edit-status"
                          data-bs-toggle="tooltip"
                          data-bs-placement="bottom" data-bs-title="Edit Status">
                    <i class="fa-regular fa-fw fa-pen-to-square"></i>
                  </button>

                  <div data-bs-toggle="tooltip"
                       data-bs-placement="bottom" data-bs-title="Delete Status">
                    <button class="c-status-button btn"
                            data-bs-toggle="modal" data-bs-target="#delete-status-modal">
                      <i class="fa-regular fa-fw fa-trash-can"></i>
                    </button>
                  </div>
                </div>
              <?php } // $status_by_client ?>

          </div>
        </div>
      <?php } else { ?>
        <div class="p-3 border-bottom text-center" id="main-status">Status deleted</div>
      <?php } // !$status->deleted ?>


      <?php if (!$status->deleted) { ?>
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
              <div class="my-auto"><span id="reply-input-counter">0</span>/280</div>
              <button class="btn btn-primary fw-bold" id="post-reply" disabled>Reply</button>
            </div>
          </div>
        </div>
      <?php } // !$status->deleted ?>

    <div class="d-flex flex-column" id="status-container"></div>

  </div>
</div>

<?php PhpComponents::confirm_modal('delete-status-modal',
    'Delete Status?',
    'This can’t be undone and it will be removed from your profile and the timeline of any accounts that follow you.'); ?>

<?php PhpComponents::footer(); ?>
<?php JsComponents::status(); ?>

<script>
    // language=js
    let statusUpdatedAt = <?= $status->updated_at == null ? 'null' : "formatStatusDate(parseMysqlDateTime(\"{$status->updated_at}\"))" ?>;
    const statusCreatedAt = formatStatusDate(parseMysqlDateTime("<?= $status->created_at ?>"));

    const main = $("#main");
    const mainStatus = $("#main-status");
    const mainStatusTime = $("#main-status-time");
    const mainStatusContent = $("#main-status-content");
    const mainStatusLikeCounter = $("#main-status-like-counter");
    const mainStatusHeart = $("#main-status-heart");
    const ancestorStatusContainer = $("#ancestor-status-container");

    function updateMainStatusTime() {
        mainStatusTime.html(statusCreatedAt);

        if (statusUpdatedAt !== null) {
            mainStatusTime.append(`, Last Updated ${statusUpdatedAt}`);
        }
    }

    updateMainStatusTime();

    const editStatusButton = $("#edit-status");
    const editStatusContainer = $("#edit-status-container");
    const editInput = $("#edit-input");
    const editInputCounter = $("#edit-input-counter");
    const postEditCancelButton = $("#post-edit-cancel");
    const postEditSubmitButton = $("#post-edit-submit");

    editInput.keyup(function () {
        const editLength = editInput.val().trim().length;
        editInputCounter.html(editLength);
        postEditSubmitButton.prop("disabled", editLength <= 0);
    });

    editStatusButton.click(function () {
        editStatusButton.blur();
        editInput.val(mainStatusContent.text());
        editInput.keyup();
        mainStatusContent.addClass("d-none");
        editStatusContainer.removeClass("d-none");
    });

    postEditCancelButton.click(function () {
        editInput.val("");
        mainStatusContent.removeClass("d-none");
        editStatusContainer.addClass("d-none");
    });

    postEditSubmitButton.click(function () {
        console.log("aaa");
        $.post("/api/edit_status", {
            status_id: <?= $status_id ?>,
            content: editInput.val().trim()
        }, function (data) {
            const {status_content, updated_at} = data;
            statusUpdatedAt = formatStatusDate(parseMysqlDateTime(updated_at));

            updateMainStatusTime();
            mainStatusContent.text(status_content);
            postEditCancelButton.click();
        });
    });

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
        }, function (data) {
            const {status_id, status_content} = data;

            const statusDiv = createStatusDiv(data);
            statusContainer.prepend(statusDiv);
            setupStatusDiv(status_id, status_content);
            replyInput.val("");
            replyInput.keyup();

            if (earliestStatusId === 0) {
                earliestStatusId = status_id;
            }
        });
    });

    $.get("/api/get_status_ancestor", {
        status_id: <?= $status_id ?>
    }, function (data) {
        const oldHeight = main.height();
        let ancestorHeight = 0;

        for (let i = 0; i < data.length; i++) {
            const {status_id, status_content} = data[i];
            const statusDiv = createStatusDiv(data[i]);

            ancestorStatusContainer.prepend(statusDiv);
            setupStatusDiv(status_id, status_content);

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
        }, function (data) {
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

    $("#delete-status-modal #confirm-button").click(function () {
        $.post("/api/delete_status", {
            status_id: <?= $status_id ?>
        }, function (json) {
            console.log(json);
            window.location.reload();
        });
    });

    fetchReply(0);

    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() === $(document).height()) {
            fetchReply(earliestStatusId)
        }
    });
</script>
</body>
</html>
