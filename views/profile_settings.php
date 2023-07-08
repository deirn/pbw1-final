<?php

use Database\Controllers\User;

global $page_title;

$client_username = $_SESSION['username'];
$user = User::get($client_username) or not_found();

$page_title = "Profile Settings";
?>

<!doctype html>
<html lang="en">

<head>
    <?php PhpComponents::header(); ?>
    <?php CssComponents::navbar(); ?>
    <?php CssComponents::profile_header(); ?>
</head>

<body>

<div class="c-container container d-flex">
    <?php PhpComponents::navbar(); ?>

  <div class="flex-grow-1 border-end">
    <div class="sticky-top bg-light">
        <?php PhpComponents::profile_header($user); ?>
    </div>

    <div class="px-3">

      <div class="mb-3">
        <label class="w-100" for="display_name">Name <span class="float-end text-danger display_name"></span></label>
        <input type="text" class="form-control input" id="display_name" placeholder="Name"
               value="<?= $user->display_name ?>">
      </div>

      <div class="mb-3">
        <label class="w-100" for="bio">Bio
          <span class="float-end text-body-secondary"><span id="bio-counter">0</span>/160</span>
        </label>
        <textarea class="form-control" id="bio" placeholder="Bio" maxlength="160"
                  rows="3"><?= $user->bio ?? '' ?></textarea>
      </div>

      <button class="btn btn-dark mb-3 float-end fw-bold" id="save">Save</button>
    </div>

  </div>
</div>

<?php PhpComponents::footer(); ?>
<script>
    const clientUsername = "<?= $client_username ?>";
    const displayName = $("#display_name");
    const bio = $("#bio");
    const bioCounter = $("#bio-counter");
    const saveButton = $("#save");

    bio.keyup(function () {
        bioCounter.html(bio.val().trim().length);
    });

    saveButton.click(function () {
        $.post("/api/edit_profile", {
            username: clientUsername,
            display_name: displayName.val(),
            bio: bio.val().trim()
        }, function (data) {
            if (data["error_field"] !== undefined) {
                $(`#${data["error_field"]}`).addClass("border-danger");
                $(`.c-hint.${data["error_field"]}`).html(`${data["error_msg"]}`);
                console.log(data);
            } else {
                window.top.location = `/profile/${clientUsername}`;
            }
        });
    });

    bio.keyup();
</script>

</body>
</html>
