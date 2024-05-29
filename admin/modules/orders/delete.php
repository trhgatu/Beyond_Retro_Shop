<?php
if (!defined("_CODE")) {
    die ("Access Denied !");
}
require_once '../class/order.php';

$order = new Order($conn);
$order->delete();

