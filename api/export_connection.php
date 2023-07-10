<?php

use Database\Controllers\Connection;
use Database\Controllers\User;
use Mpdf\Mpdf;
use Mpdf\MpdfException;

if (((int)$_ENV['FEAT_EXPORT_CONNECTIONS']) == 0) not_found();

$username = $_GET['username'];

$user = User::get_by_username($username) or not_found();
$following = Connection::get_following($user->username);
$followers = Connection::get_followers($user->username);

function print_connection(Connection $connection, bool $following): void
{
    $user = $following
        ? $connection->resolve_following()
        : $connection->resolve_follower();
    ?>

  <tr>
    <td>@<?= $user->username ?></td>
    <td><?= $user->html_display_name() ?></td>
  </tr>

    <?php
}

ob_start();
?>

  <h3>User following <?= $user->html_display_name() ?> (@<?= $user->username ?>)</h3>

  <table>
    <thead>
    <tr>
      <th>Username</th>
      <th>Display Name</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($followers as $con) {
        print_connection($con, false);
    } ?>
    </tbody>
  </table>

  <h3>User followed by <?= $user->html_display_name() ?> (@<?= $user->username ?>)</h3>

  <table>
    <thead>
    <tr>
      <th>Username</th>
      <th>Display Name</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($following as $con) {
        print_connection($con, true);
    } ?>
    </tbody>
  </table>

<?php
$data = ob_get_clean();
$mpdf = new Mpdf();

try {
    $mpdf->WriteHTML($data);
    $mpdf->Output("connections_{$user->username}.pdf", 'D');
} catch (MpdfException $e) {
    server_error();
}
