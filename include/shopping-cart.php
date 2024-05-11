<?php
session_start();
include "../user/config.php";
require_once ("../db_function/connect.php");
require_once ("../db_function/functions.php");
require_once ("../db_function/database.php");
require_once ("../db_function/session.php");
require_once '../class/product.php';
require_once '../class/category.php';
require_once '../class/cart.php';
$data = [
    'pageTitle' => 'Giỏ hàng'
];
//Kiểm tra trạng thái đăng nhập
if (!isUserLogin()) {
    redirect('../user/?module=authen&action=login');
}
if (isPost() && isset($_POST['update-cart'])) {
    $cart = new Cart($conn);
    $cart->updateCart();

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
</head>

<body>

    <?php
    layout('header', $data);
    ?>
    <!-- Header Section End -->

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Giỏ hàng</h4>
                        <div class="breadcrumb__links">
                            <a href="./index.html">Home</a>
                            <a href="http://localhost/Beyond_Retro/include/shop.php">Shop</a>
                            <span>Giỏ hàng</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <form method="post">
        <section class="shopping-cart spad">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="shopping__cart__table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Số lượng</th>
                                        <th>Tổng</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php
                                        $totalPrice = 0;
                                        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                                            foreach ($_SESSION['cart'] as $productId => $quantity) {
                                                $product = new Product($conn);
                                                $productDetail = $product->getByProductId($productId);
                                                ?>
                                                <?php
                                                foreach ($productDetail as $item) {
                                                    ?>
                                                <tr>
                                                    <td class="product__cart__item">
                                                        <div class="product__cart__item__pic">
                                                            <img src="../images/products/thumbnail/<?php echo $item['thumbnail']; ?>" alt=""
                                                                style="max-width: 90px">
                                                        </div>
                                                        <div class="product__cart__item__text">
                                                            <h6><?php echo $item['name']; ?></h6>
                                                            <h5><?php echo number_format($item['price'], 0, ',', '.') ?>₫</h5>
                                                        </div>
                                                    </td>
                                                    <td class="quantity__item">
                                                        <div class="quantity">
                                                            <div class="pro-qty-2"
                                                                style="border: 1px solid #e5e5e5;position: relative;width: 55px">
                                                                <input type="number" name="quantity[<?php echo $productId; ?>]"
                                                                    value="<?php echo $quantity; ?>">

                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="cart__price">
                                                        <?php echo number_format($item['price'] * $quantity, 0, ',', '.') ?>₫
                                                    </td>

                                                    <td class="cart__close"><a
                                                            href="../user/?module=cart&action=removeproduct&id=<?php echo $productId; ?>"><i
                                                                class="fa fa-close"></i></a></td>
                                                </tr>
                                                <?php
                                                $totalPrice += $item['price'] * $quantity;
                                                }
                                                ?>
                                            <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='4'>Giỏ hàng của bạn đang trống!</td></tr>";
                                        }
                                        ?>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="continue__btn">
                                    <a href="http://localhost/Beyond_Retro/include/shop.php">Tiếp tục mua sắm</a>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="continue__btn update__btn">
                                    <input type="submit" value="Cập nhật giỏ hàng" class="primary-btn"
                                        name="update-cart">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="cart__total">
                            <h6>Tổng giỏ hàng</h6>
                            <ul>

                                <li>Tổng tiền<span><?php echo number_format($totalPrice, 0, ',', '.') ?>₫</span></li>
                            </ul>
                            <a href="http://localhost/Beyond_Retro/include/checkout.php" class="primary-btn">Thanh
                                toán</a>
                        </div>


                    </div>

                </div>
            </div>

        </section>
    </form>
    <?php
    layout('footer', $data)
        ?>
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch">+</div>
            <form class="search-model-form">
                <input type="text" id="search-input" placeholder="Search here.....">
            </form>
        </div>
    </div>
    <!-- Search End -->

    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/jquery.nicescroll.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>