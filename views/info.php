<?php
global $page_title;

$page_title = "Info";
?>

<!doctype html>
<html lang="en">

<head>
    <?php PhpComponents::header(); ?>
    <?php CssComponents::navbar(); ?>
</head>

<body>

<div class="c-container container p-0 d-flex">
    <?php PhpComponents::navbar(); ?>

  <div class="flex-grow-1 d-flex flex-column border-start border-end">
    <div class="flex-grow-1 position-relative" id="main">
      <div class="position-absolute top-50 start-50 translate-middle d-flex flex-column justify-content-center">
        <div class="d-flex justify-content-center mb-4">
          <i class="fa-solid fa-5x fa-bug fa-spin-pulse"></i>
        </div>

        <h5 class="text-center"><span class="text-body-tertiary">by</span> <span>Dimas Firmansyah</span></h5>
        <div class="d-flex gap-3 justify-content-center">
          <a href="https://deirn.bai.lol" class="link-secondary"><i class="fa-solid fa-fw fa-globe"></i></a>
          <a href="https://github.com/deirn/pbw1-final" class="link-secondary"><i class="fa-brands fa-fw fa-github"></i></a>
        </div>
      </div>
    </div>
      <?php PhpComponents::navbar_mobile(); ?>
  </div>
</div>

<?php PhpComponents::footer(); ?>

</body>
</html>
