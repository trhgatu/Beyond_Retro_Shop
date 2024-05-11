<?php
session_start();
include "../user/config.php";
require_once ("../db_function/connect.php");
require_once ("../db_function/functions.php");
require_once ("../db_function/database.php");
require_once ("../db_function/session.php");
require_once '../class/product.php';
require_once '../class/category.php';
require_once '../class/order.php';
$data = [
    'pageTitle' => 'Thanh toán'
];
if (!isUserLogin()) {
    redirect('../user/?module=authen&action=login');
}
$order = new Order($conn);
if (isPost()) {
    $orderStatus = $order->placeOrder();
}
$user_id = getSession('user_id');
$userResult = oneRaw("SELECT fullname, email, phone_number FROM user WHERE id = $user_id");
$addressResult = oneRaw("SELECT address, district, city, country FROM addresses WHERE user_id = $user_id AND is_default = 1");
$error = getFlashData('error');
$old = [
    'fullname' => $userResult['fullname'],
    'email' => $userResult['email'],
    'phone_number' => $userResult['phone_number'],
    'address' => $addressResult['address'],
    'district' => $addressResult['district'],
    'city' => $addressResult['city'],
    'country' => $addressResult['country'],
];

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$userDetails = getFlashData('user-detail');
if ($userDetails) {
    $old = $userDetails;
}

?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Male_Fashion Template">
    <meta name="keywords" content="Male_Fashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>

<body>
    <?php layout('header', $data) ?>
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Thanh toán</h4>
                        <div class="breadcrumb__links">
                            <a href="./index.html">Home</a>
                            <a href="http://localhost/Beyond_Retro/include/shop.php">Shop</a>
                            <span>Thanh toán</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="checkout spad">
        <div class="container">
            <div class="checkout__form">
                <?php
                if (!empty($msg)) {
                    getMSG($msg, $msg_type);
                } ?>
                <form method="post">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <h6 class="checkout__title">Chi tiết đơn hàng</h6>
                            <div class="checkout__input">
                                <p>Họ tên<span>*</span></p>
                                <input type="text" name="fullname" value="<?php echo $old['fullname']; ?>">
                                <?php
                                echo form_error('fullname', '<span class= "error">', '</span>', $error);
                                ?>
                            </div>
                            <div class=" checkout__input">
                                <p>Địa chỉ<span>*</span></p>
                                <input type="text" placeholder="Street Address" class="checkout__input__add"
                                    name="address" value="<?php echo $old['address']; ?>">
                            </div>
                            <div class="checkout__input">
                                <p>Khu vực<span>*</span></p>
                                <input type="text" placeholder="" name="district"
                                    value="<?php echo $old['district'] ?>">
                            </div>
                            <div class="checkout__input">
                                <p>Thành phố<span>*</span></p>
                                <input type="text" name="city" value="<?php echo $old['city'] ?>">
                            </div>
                            <div class="checkout__input">
                                <p>Quốc gia<span>*</span></p>
                                <input type="text" name="country" value="<?php echo $old['country'] ?>">
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Số điện thoại<span>*</span></p>
                                        <input type="text" name="phone_number"
                                            value="<?php echo $old['phone_number']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Email<span>*</span></p>
                                        <input type="text" name="email" value="<?php
                                        echo $old['email']; ?>">
                                        <?php
                                        echo form_error('email', '<span class= "error">', '</span>', $error);
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="checkout__input">
                                <p>Ghi chú cho đơn hàng<span></span></p>
                                <input type="text" placeholder="Ghi chú cho đơn hàng của bạn." name="note">
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <div class="checkout__order">
                                <h4 class="order__title">Đơn hàng của bạn</h4>
                                <ul class="checkout__total__products">
                                    <div class="checkout__order__products">
                                        <h style="font-weight: bold">Sản phẩm</h>
                                    </div>
                                    <ul class="checkout__total__all">
                                        <?php
                                        $totalPrice = 0;
                                        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])):
                                            foreach ($_SESSION['cart'] as $productId => $quantity):
                                                $product = new Product($conn);
                                                $productDetail = $product->getByProductId($productId);
                                                ?>
                                                <?php
                                                foreach ($productDetail as $item):
                                                    ?>
                                                    <div class="product__cart__item__pic">
                                                        <span class="product__cart__item__quantity"><?php echo $quantity; ?></span>
                                                        <input type="hidden" name="num" id="num" value="<?php echo $quantity; ?>">

                                                        <img src="../images/products/thumbnail/<?php echo $item['thumbnail']; ?>"
                                                            alt="" style="max-width: 70px; border: 1px solid #e1e1e1">
                                                        <li><?php echo $item['name'] ?><span><?php echo number_format($item['price'] * $quantity, 0, ',', '.') ?>₫</span>
                                                        </li>
                                                    </div>
                                                    <?php
                                                    $totalPrice += $item['price'] * $quantity;
                                                    ?>
                                                    <input type="hidden" name="price" id="price" value="<?php echo $totalPrice; ?>">
                                                    <?php
                                                endforeach;
                                                ?>
                                                <?php
                                            endforeach;
                                            ?>
                                        </ul>
                                        </hr>
                                        <h3>Tổng cộng: <span><?php echo number_format($totalPrice, 0, ',', '.') ?>₫</span>
                                            <input type="hidden" name="totalPrice" id="totalPrice"
                                                value="<?php echo $totalPrice; ?>">
                                        </h3>
                                        <?php
                                        endif;
                                        ?>
                                </ul>
                                <style>
                                    .product__cart__item__quantity {
                                        position: absolute;
                                        left: 103px;
                                        padding: 3px 8px;
                                        background-color: rgba(153, 153, 153, 0.9);
                                        color: #ffffff;
                                        font-size: 10px;
                                        font-weight: bold;
                                        border-radius: 50%;
                                        margin-top: -8px;
                                    }
                                </style>

                                <button type="submit" class="site-btn">Đặt hàng</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- Footer Section Begin -->
    <?php layout('footer', $data); ?>
    <!-- Footer Section End -->
</body>
</html>