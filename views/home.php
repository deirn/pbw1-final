<?php
$client_username = $_SESSION['username'];

$page_title = "Home";
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

  <div class="flex-grow-1 border-end">
    <div class="d-flex flex-column" id="status-container">
    </div>
  </div>
</div>

<?php PhpComponents::footer(); ?>
<?php JsComponents::tooltip(); ?>
<?php JsComponents::status(); ?>

<script>
    const clientUsername = "<?= $client_username ?>";

    function fetchStatus(idAfter) {
        $.get("/api/get_home_status.php", {
            username: clientUsername,
            id_before: idAfter
        }, statusHandler);
    }

    fetchStatus(0);

    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() === $(document).height()) {
            fetchStatus(earliestStatusId)
        }
    });
</script>
</body>
</html>
