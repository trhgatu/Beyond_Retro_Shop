<!-- Kích hoạt tài khoản -->
<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
require_once '../class/authen.php';
$authen = new Authen($conn);
$authen->active();