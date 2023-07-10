<?php
global $error_code;
global $error_message;
global $page_title;

$page_title = "{$error_code}";
?>

<!doctype html>
<html lang="en">

<head>
    <?php PhpComponents::header(); ?>

  <title><?= $error_code ?></title>
</head>

<body>

<div class="position-absolute w-25 top-50 start-50 translate-middle text-center">
  <i class="fa-solid fa-5x fa-bug fa-spin-pulse mb-4"></i>
  <h1>404</h1>
    <?= $error_message ?>
  <a href="javascript:history.back()">Go back.</a>
</div>

<?php PhpComponents::footer(); ?>

</body>
</html>
