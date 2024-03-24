<?php
if (!defined("_CODE")) {
    die ("Access Denied !");
}
require_once '../class/product.php';

$product = new Product($conn);
$product->deleteProduct();

