<?php

require_once __DIR__ . '/index.php';

?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?= $_ENV['SITE_TITLE'] ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"
          integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
</head>
<body>

<?php

$request = strtok($_SERVER['REQUEST_URI'], '?');

if ($request == '/auth') {
    require __DIR__ . '/views/auth.php';
} elseif (!isset($_SESSION['username']) || !isset($_COOKIE['login'])) {
    require __DIR__ . '/api/auth.php';
} else {
    switch ($request) {
        case '':
        case '/':
            require __DIR__ . '/views/home.php';
            break;

        default:
            http_response_code(404);
            require __DIR__ . '/views/404.php';
            break;
    }
}

?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>
</html>
