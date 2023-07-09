<?php

use Database\Controllers\Connection;
use Database\Controllers\User;

global $slug_matches;
global $page_title;

$client_username = $_SESSION['username'];
$slug_username = $slug_matches[1];
$slug_type = $slug_matches[2];

$is_following_tab = $slug_type == 'following';

$user = User::get_by_username($slug_username) or not_found();
$connections = $is_following_tab
    ? Connection::get_following($user->username)
    : Connection::get_followers($user->username);

$page_title = $is_following_tab
    ? "People followed by {$user->display_name} (@{$user->username})"
    : "People following {$user->display_name} (@{$user->username})";
?>

<!doctype html>
<html lang="en">

<head>
    <?php PhpComponents::header(); ?>
    <?php CssComponents::navbar(); ?>
    <?php CssComponents::profile_header(); ?>

  <style>
      .c-tab .nav-link {
          padding-top: 0.75rem;
          padding-bottom: 0.75rem;
      }

      .c-avatar {
          width: 3rem;
          height: 3rem;
          background-color: var(--bs-gray-300);
          border-radius: 50%;
          overflow: hidden;

          background-image: url("/assets/media/noavatar.svg");
          background-position: center;
          background-repeat: no-repeat;
          background-size: auto 100%;
      }

      .c-avatar img {
          width: 100%;
          height: auto;
      }

      .c-user:hover {
          cursor: pointer;
          background-color: var(--bs-gray-200);
      }

      .c-profile-button {
          min-width: 6.5em;
      }

      .c-profile-button.c-unfollow > span:before {
          content: "Following";
      }

      .c-profile-button.c-unfollow:hover {
          background-color: var(--bs-danger-bg-subtle);
          border-color: var(--bs-danger-border-subtle) !important;
      }

      .c-profile-button.c-unfollow:hover > span:before {
          color: var(--bs-danger);
          content: "Unfollow";
      }
  </style>
</head>

<body>

<div class="c-container container p-0 d-flex">
    <?php PhpComponents::navbar(); ?>

  <div class="flex-grow-1 d-flex flex-column border-start border-end">
    <div class="flex-grow-1" id="main">
      <div class="sticky-top bg-light">
          <?php PhpComponents::profile_header($user); ?>

        <ul class="c-tab nav nav-underline nav-fill border-bottom">
          <li class="nav-item">
            <a class="nav-link link-body-emphasis <?= $is_following_tab ? 'active' : '' ?>"
               href="/profile/<?= $user->username ?>/following">Following</a>
          </li>

          <li class="nav-item">
            <a class="nav-link link-body-emphasis <?= $is_following_tab ? '' : 'active' ?>"
               href="/profile/<?= $user->username ?>/followers">Followers</a>
          </li>
        </ul>
      </div>

      <ul class="nav flex-column">
          <?php
          foreach ($connections as $connection) {
              $connection_user = $is_following_tab ? $connection->resolve_following() : $connection->resolve_follower();
              $followed_by_client = Connection::get($client_username, $connection_user->username) != null;
              ?>

            <li class="c-user nav-item d-flex px-3 py-2 gap-3"
                data-followed="<?= $followed_by_client ? 'true' : 'false' ?>"
                data-username="<?= $connection_user->username ?>">

              <div class="c-avatar flex-shrink-0 mb-auto">
                <img src="/assets/media/avatar/<?= $connection_user->avatar ?>" alt="">
              </div>

              <div class="d-flex flex-column flex-grow-1">
                <div class="d-flex">
                  <div class="flex-grow-1">
                    <a href="/profile/<?= $connection_user->username ?>"
                       class="link-body-emphasis link-underline link-underline-opacity-0 link-underline-opacity-100-hover fw-bold">
                        <?= $connection_user->display_name ?>
                    </a>

                      <?php PhpComponents::profile_username($connection_user->username) ?>

                  </div>

                  <div class="c-profile-buttons my-auto">
                    <button class="c-profile-button c-unfollow btn btn-light border border-dark-subtle fw-bold">
                      <span></span>
                    </button>
                    <button class="c-profile-button c-follow btn btn-dark fw-bold">Follow</button>
                  </div>
                </div>
                <div><?= $connection_user->bio ?? '' ?></div>
              </div>

            </li>
          <?php } ?>
      </ul>
    </div>

      <?php PhpComponents::navbar_mobile(); ?>
  </div>
</div>

<?php PhpComponents::footer(); ?>

<script>
    const clientUsername = "<?= $client_username ?>";

    $(".c-tab .nav-link").click(function (e) {
        e.preventDefault();
        history.replaceState({}, '', $(this).attr("href"));
        location.reload();
    });

    $(".c-user").each(function () {
        const user = $(this);
        const username = user.data("username");
        const followButton = user.find(".c-follow");
        const unfollowButton = user.find(".c-unfollow");

        user.click(function () {
            if (hasTextSelected()) return;
            window.location.href = `/profile/${username}`;
        });

        followButton.click(clickButton("follow"));
        unfollowButton.click(clickButton("unfollow"));

        function clickButton(type) {
            return function (e) {
                e.stopPropagation();

                $.post("/api/follow", {
                    type,
                    follower: clientUsername,
                    following: username
                }, function (data) {
                    if (data["follower_count"] !== undefined) {
                        user.data("followed", !user.data("followed"));
                        toggleButtons();
                    }
                });
            }
        }

        function toggleButtons() {
            if (user.data("followed")) {
                followButton.hide();
                unfollowButton.show();
            } else {
                followButton.show();
                unfollowButton.hide();
            }
        }

        toggleButtons();
    });
</script>
</body>
</html>
