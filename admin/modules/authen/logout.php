<!-- Đăng xuất tài khoản -->
<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
if (isLogin()) {
    $token = getSession('tokenlogin');
    delete('tokenlogin', "token='$token'");
    removeSession('tokenlogin');
    redirect('?module=authen&action=login');
}
?>