<?php
$page_title = "Home";
?>

<!doctype html>
<html lang="en">

<head>
    <?php PhpComponents::header(); ?>
    <?php CssComponents::navbar(); ?>
</head>

<body>

<div class="container d-flex">
    <?php PhpComponents::navbar(); ?>

  <div class="flex-grow-1 p-3 border-end">
  </div>
</div>

<?php PhpComponents::footer(); ?>
<?php JsComponents::tooltip(); ?>

</body>
</html>