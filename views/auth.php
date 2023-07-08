<?php
global $page_title;

$new = isset($_GET['new']);
session_destroy();

unset($_COOKIE['login']);
setcookie('login', '', time() - 3600, '/');

$page_title = $new ? 'Sign Up' : 'Login';
?>

<!doctype html>
<html lang="en">

<head>
    <?php PhpComponents::header(); ?>

  <style>
      .c-auth {
          width: 25em;
      }

      .c-label {
          width: 100%;
      }

      .c-label > .c-error-text {
          float: right;
      }
  </style>
</head>

<body>

<div class="c-auth position-absolute top-50 start-50 translate-middle text-center">
  <i class="fa-solid fa-3x fa-bug fa-spin-pulse mb-4"></i>
  <h2><?= $page_title ?></h2>

    <?php if ($new) { ?>
      <div class="mb-3 text-start">
        <label class="c-label" for="display_name">Name <span class="text-danger c-error-text display_name"></span></label>
        <input type="text" class="form-control input" id="display_name" placeholder="Name">
      </div>
    <?php } ?>

  <div class="mb-3 text-start">
    <label class="c-label" for="username">Username <span class="text-danger c-error-text username"></span></label>
    <input type="text" class="form-control input" id="username" placeholder="Username">
  </div>
  <div class="mb-3 text-start">
    <label class="c-label" for="password">Password <span class="text-danger c-error-text password"></span></label>
    <input type="password" class="form-control input" id="password" placeholder="Password">
  </div>

    <?php if ($new) { ?>
      <div class="mb-3 text-start">
        <label class="c-label" for="repeat_password">Repeat Password <span
            class="text-danger c-error-text repeat_password"></span></label>
        <input type="password" class="form-control input" id="repeat_password" placeholder="Password">
      </div>
      <button class="btn btn-primary mb-3" id="sign-up">Sign Up</button>
      <br/>
      Have an account? <a href="/auth">Login.</a>
    <?php } else { ?>
      <button class="btn btn-primary mb-3" id="login">Login</button>
      <br/>
      Don't have an account? <a href="?new">Create a new one.</a>
    <?php } ?>

</div>

<?php PhpComponents::footer(); ?>

<?php if ($new) { ?>
  <script>
      $("#sign-up").click(function () {
          const displayName = $("#display_name");
          const username = $("#username");
          const password = $("#password");
          const repeatPassword = $("#repeat_password");

          $.post("/api/sign_up", {
              username: username.val(),
              password: password.val(),
              repeat_password: repeatPassword.val(),
              display_name: displayName.val()
          }, function (data) {
              if (data["error_field"] !== undefined) {
                  $(`#${data["error_field"]}`).addClass("border-danger");
                  $(`.c-hint.${data["error_field"]}`).html(`${data["error_msg"]}`);
              } else {
                  window.top.location = "/auth";
              }
          });
      })
  </script>
<?php } else { ?>
  <script>
      $("#login").click(function () {
          const username = $("#username");
          const password = $("#password");

          $.post("/api/login", {
              username: username.val(),
              password: password.val(),
          }, function (data) {
              if (data["error_field"] !== undefined) {
                  $(`#${data["error_field"]}`).addClass("border-danger");
                  $(`.c-hint.${data["error_field"]}`).html(`${data["error_msg"]}`);
              } else {
                  window.top.location = "/";
              }
          });
      });
  </script>
<?php } ?>

<script>
    $(".input").on("input", function () {
        $(this).siblings().children(".c-hint").html("");
        $(this).removeClass("border-danger");
    });
</script>
</body>
</html>
