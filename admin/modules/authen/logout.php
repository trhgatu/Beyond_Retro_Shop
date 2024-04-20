<!-- Đăng xuất tài khoản -->
<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
require_once '../class/authen.php';
$authen = new Authen($conn);
if (isAdminLogin()) {
    $authen->logout_admin();
}
?>
