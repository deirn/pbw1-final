<?php

use Controllers\Database\Connection;
use Controllers\Database\User;

global $slug_matches;

$client_username = $_SESSION['username'];
$slug_username = $slug_matches[1];

$user = User::get($slug_username);
$followers = Connection::get_followers_count($user->username);
$following = Connection::get_following_count($user->username);

$user_is_client = $user->username == $client_username;
$user_is_followed = !$user_is_client && (Connection::get($client_username, $user->username) != null);

$page_title = "{$user->display_name} (@{$user->username})";
?>

<!doctype html>
<html lang="en">

<head>
    <?php PhpComponents::header(); ?>
    <?php CssComponents::navbar(); ?>
    <?php CssComponents::profile_header(); ?>

  <style>
      .c-banner-picture {
          background-color: var(--bs-gray-400);
          height: 13rem;
      }

      .c-avatar-holder {
          position: relative;
          height: 4.5rem;
      }

      .c-avatar {
          position: absolute;
          height: 9rem;
          width: 9rem;
          border: 4px solid var(--bs-light);
          border-radius: 50%;
          background-color: #0dcaf0;
          translate: 1rem -50%;
      }

      .c-buttons {
          margin-left: auto;
          width: fit-content;
      }

      .c-buttons > button {
          min-width: 6.5em;
      }

      #unfollow-button > span:before {
          content: "Following";
      }

      #unfollow-button:hover {
          background-color: var(--bs-danger-bg-subtle);
          border-color: var(--bs-danger-border-subtle) !important;
      }

      #unfollow-button:hover > span:before {
          color: var(--bs-danger);
          content: "Unfollow";
      }
  </style>
</head>

<body>

<div class="c-container container d-flex">
    <?php PhpComponents::navbar(); ?>

  <div class="flex-grow-1 border-end">
    <div class="sticky-top bg-light">
        <?php PhpComponents::profile_header($user); ?>
    </div>

    <div class="border-bottom pb-3">
      <div class="c-banner-picture border-bottom"></div>

      <div class="c-avatar-holder">
        <div class="c-avatar"></div>
        <div class="c-buttons pt-3 pe-3">
            <?php if ($user_is_client) { ?>
              <a href="/settings/profile" class="btn btn-light border border-dark-subtle fw-bold">
                Edit Profile
              </a>
            <?php } else { ?>
              <button class="btn btn-light border border-dark-subtle fw-bold" id="unfollow-button">
                <span></span>
              </button>
              <button class="btn btn-dark fw-bold" id="follow-button">Follow</button>
            <?php } ?>
        </div>
      </div>

      <div class="px-3 d-flex flex-column gap-2">
        <div>
          <div class="fw-bold fs-5"><?= $user->display_name ?></div>

            <?php PhpComponents::profile_username($user) ?>

        </div>

          <?php if ($user->bio != null) { ?>
            <div><?= $user->bio ?></div>
          <?php } ?>

        <div class="d-flex gap-3">
          <a href="/profile/<?= $user->username ?>/following"
             class="link-body-emphasis link-underline link-underline-opacity-0 link-underline-opacity-100-hover">
            <span class="fw-bold" id="following-count"><?= $following ?></span> Following
          </a>
          <a href="/profile/<?= $user->username ?>/followers"
             class="link-body-emphasis link-underline link-underline-opacity-0 link-underline-opacity-100-hover">
            <span class="fw-bold" id="follower-count"><?= $followers ?></span> Followers
          </a>
        </div>
      </div>
    </div>

  </div>
</div>

<?php PhpComponents::footer(); ?>
<?php JsComponents::tooltip(); ?>

<?php if (!$user_is_client) { ?>
  <script>
      const viewedUsername = "<?= $user->username ?>";
      const clientUsername = "<?= $client_username ?>";

      let followed = <?= $user_is_followed ? 'true' : 'false' ?>;

      const followButton = $("#follow-button");
      const unfollowButton = $("#unfollow-button");

      followButton.click(clickButton("follow"));
      unfollowButton.click(clickButton("unfollow"));

      function clickButton(type) {
          return function () {
              $.post("/api/follow.php", {
                  type,
                  follower: clientUsername,
                  following: viewedUsername
              }, function (json) {
                  const data = JSON.parse(json);

                  if (data["follower_count"] !== undefined) {
                      followed = !followed;
                      $("#follower-count").html(data["follower_count"]);
                      toggleButtons();
                  }
              });
          }
      }

      function toggleButtons() {
          if (followed) {
              followButton.hide();
              unfollowButton.show();
          } else {
              followButton.show();
              unfollowButton.hide();
          }
      }

      toggleButtons();
  </script>
<?php } ?>

</body>
</html>