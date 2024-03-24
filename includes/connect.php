<!-- Kết nối với database -->
<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
try {
    if (class_exists('PDO')) {
        $dsn = 'mysql:dbname=' . _DB . ';host=' . _HOST;
        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];
        $conn = new PDO($dsn, _USER, _PASS, $options);

    }
} catch (Exception $exception) {
    echo $exception->getMessage();
    die();
}