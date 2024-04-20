<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
include '..class/account.php';
$account = new Account($conn);

if (!isUserLogin()) {
    redirect('../user/?module=authen&action=login');
}

if (isPost()) {
    $account->updateInfo();

}
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$error = getFlashData('error');
$old = getFlashData('old');