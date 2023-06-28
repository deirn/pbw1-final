<?php

$new = isset($_GET['new']);
session_destroy();

unset($_COOKIE['login']);
setcookie('login', '', time() - 3600, '/');

?>

<div class="position-absolute w-25 top-50 start-50 translate-middle text-center">
  <h2><?= $new ? 'Sign Up' : 'Login' ?></h2>

    <?php if ($new) { ?>
      <div class="mb-3 text-start">
        <label for="display_name">Name <span class="text-danger error-text display_name"></span></label>
        <input type="text" class="form-control input" id="display_name" placeholder="Name">
      </div>
    <?php } ?>

  <div class="mb-3 text-start">
    <label for="username">Username <span class="text-danger error-text username"></span></label>
    <input type="text" class="form-control input" id="username" placeholder="Username">
  </div>
  <div class="mb-3 text-start">
    <label for="password">Password <span class="text-danger error-text password"></span></label>
    <input type="password" class="form-control input" id="password" placeholder="Password">
  </div>

    <?php if ($new) { ?>
      <div class="mb-3 text-start">
        <label for="repeat_password">Repeat Password <span
            class="text-danger error-text repeat_password"></span></label>
        <input type="password" class="form-control input" id="repeat_password" placeholder="Password">
      </div>
      <button class="btn btn-primary mb-3" id="sign-up">Sign Up</button>
      <br/>
      Have an account? <a href="auth">Login.</a>
    <?php } else { ?>
      <button class="btn btn-primary mb-3" id="login">Login</button>
      <br/>
      Don't have an account? <a href="?new">Sign up.</a>
    <?php } ?>

</div>

<?php if ($new) { ?>
  <script>
      $("#sign-up").click(function () {
          const display_name = $("#display_name");
          const username = $("#username");
          const password = $("#password");
          const repeat_password = $("#repeat_password");

          $.post("/api/sign_up.php", {
              username: username.val(),
              password: password.val(),
              repeat_password: repeat_password.val(),
              display_name: display_name.val()
          }, function (json) {
              const data = JSON.parse(json);

              if (data["error_field"] !== undefined) {
                  $(`#${data["error_field"]}`).addClass("border-danger");
                  $(`.error-text.${data["error_field"]}`).html(`(${data["error_msg"]})`);
                  console.log(data);
              } else {
                  window.top.location = "/auth";
              }
          });
      })

      $(".input").on("input", function () {
          $(this).siblings().children(".error-text").html("");
          $(this).removeClass("border-danger");
      });
  </script>
<?php } else { ?>
  <script>
    $("#login").click(function () {
        const username = $("#username");
        const password = $("#password");

        $.post("/api/login.php", {
            username: username.val(),
            password: password.val(),
        }, function (json) {
            const data = JSON.parse(json);

            if (data["error_field"] !== undefined) {
                $(`#${data["error_field"]}`).addClass("border-danger");
                $(`.error-text.${data["error_field"]}`).html(`(${data["error_msg"]})`);
                console.log(data);
            } else {
                window.top.location = "/";
            }
        });
    });
  </script>
<?php } ?>
