<?php
if (!defined("_CODE")) {
    die ("Access Denied !");
}
require_once '../class/product.php';
$data = [
    'pageTitle' => 'Thêm sản phẩm mới'
];
$product = new Product($conn);
$deleteStatus = $product->deleteProduct();
if ($deleteStatus) {
    setFlashData('msg', 'Xóa sản phẩm thành công.');
    setFlashData('msg_type', 'success');
}