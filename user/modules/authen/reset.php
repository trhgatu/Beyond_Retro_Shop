<!-- Reset mật khẩu -->
<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
$data = [
    'pageTitle' => 'Khôi phục mật khẩu'
];
require_once '../class/authen.php';

$authen = new Authen($conn);
$authen->reset();

?>