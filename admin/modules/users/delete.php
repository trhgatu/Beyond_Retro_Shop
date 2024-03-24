<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}

require_once '../class/user.php';
$user = new User($conn);
$user->deleteUser();
