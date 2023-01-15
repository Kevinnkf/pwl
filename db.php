<?php
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'uas';
$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

if (!$conn) {
    # code...
    die('Gagal terhubung ke mysql:'.mysqli_connect_error());
}
?>