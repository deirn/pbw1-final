<?php
$page_title = "404";
?>

<!doctype html>
<html lang="en">

<head>
    <?php PhpComponents::header(); ?>

  <title>404</title>
</head>

<body>

<div class="position-absolute w-25 top-50 start-50 translate-middle text-center">
  <i class="fa-solid fa-5x fa-bug fa-spin-pulse mb-4"></i>
  <h1>404</h1>
  Where are you going? <a href="javascript:history.back()">Go back.</a>
</div>

<?php PhpComponents::footer(); ?>

</body>
</html>
