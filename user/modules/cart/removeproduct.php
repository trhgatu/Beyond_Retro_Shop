<?php
require_once ("../db_function/connect.php");
require_once ("../db_function/functions.php");
require_once ("../db_function/database.php");
require_once ("../db_function/session.php");
require_once '../class/cart.php';
//Kiểm tra trạng thái đăng nhập
if (!isUserLogin()) {
    redirect('../user/?module=authen&action=login');
}

if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    if (isset($_SESSION['cart'][$productId])) {
        $cart = new Cart($conn);
        $cart->removeFromCart($productId);
        redirect("http://localhost/Beyond_Retro/include/shopping-cart.php");
    } else {
        echo "Không tìm thấy sản phẩm trong giỏ hàng";
    }
} else {
    echo "Không có sản phẩm nào được chọn để xóa";
}

