<?php
require_once '../class/address.php';

if (!isset($_SESSION['tokenlogin'])) {
    header("Location: ../user/?module=authen&action=login");
    exit;
}
$address = new Address($conn);
$address->deleteAddress();