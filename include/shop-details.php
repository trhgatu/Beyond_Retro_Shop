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
    'pageTitle' => 'Shop',
];

if (isPost()) {
    $cart = new Cart($conn);
    $cart->addToCart();
}
$product = new Product($conn);
$productId = $_GET['id'];
$productDetail = $product->getByProductId($productId);
$productImages = $product->getImagesByProductId($productId);
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

</head>

<body>
    <!-- Page Preloder -->
    <?php
    layout('header', $data);
    ?>

    <!-- Shop Details Section Begin -->

    <?php
    if (!empty($productDetail)) {
        ?>
        <section class="shop-details" style="background-color: #fff">
            <div class="product__details__pic">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="product__details__breadcrumb">
                                <a href="<?php echo BASE_URL ;?>index.php">Home</a>
                                <a href="<?php echo BASE_URL ;?>shop.php">Shop</a>
                                <span>Chi tiết sản phẩm</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3">
                            <ul class="nav nav-tabs" role="tablist">
                                <?php
                                foreach ($productDetail as $item):
                                    if (!empty($productImages)) {
                                        ?>
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">

                                                <img class="product__thumb__pic set-bg"
                                                    src="../images/products/thumbnail/<?php echo $item['thumbnail']; ?>">
                                            </a>
                                        </li>
                                        <?php

                                    }
                                    $tab_index = 1;
                                    foreach ($productImages as $key => $image) {
                                        ?>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#tabs-<?php echo $tab_index + 1; ?>"
                                                role="tab">
                                                <img class="product__thumb__pic set-bg"
                                                    src="../images/products/gallery/<?php echo $image['images_path']; ?>">
                                            </a>
                                        </li>
                                        <?php
                                        $tab_index++;
                                    }
                                    ?>
                                </ul>
                            </div>
                            <div class="col-lg-6 col-md-9">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                        <div class="product__details__pic__item">
                                            <img src="../images/products/thumbnail/<?php echo $item['thumbnail']; ?>">
                                        </div>
                                    </div>
                                    <?php
                                    $tab_panel_index = 1;

                                    if (!empty($productImages)) {
                                        foreach ($productImages as $key => $image) {
                                            ?>
                                            <div class="tab-pane" id="tabs-<?php echo $tab_panel_index + 1; ?>" role="tabpanel">
                                                <div class="product__details__pic__item">
                                                    <img src="../images/products/gallery/<?php echo $image['images_path'] ?>">
                                                </div>
                                            </div>
                                            <?php
                                            $tab_panel_index++;
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="product__details__content">
                    <div class="container">
                        <div class="row d-flex justify-content-center">
                            <div class="col-lg-8">
                                <div class="product__details__text">
                                    <h4><?php echo $item['name'] ?></h4>
                                    <h3> <?php echo number_format($item['price'], 0, ',', '.') ?>₫</h3>
                                    <form method="post">
                                        <div class="product__details__cart__option">
                                            <div class="quantity">
                                                <div class="pro-qty">
                                                    <input type="number" name="quantity" value="1" min="1">
                                                </div>
                                            </div>
                                            <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                                            <input type="submit" name="addtocart" value="Thêm vào giỏ hàng" class="primary-btn">
                                        </div>
                                    </form>

                                    <div class="product__details__last__option">
                                        <h5>
                                                <?php
                                                $sql = "SELECT c.id, c.name FROM category c JOIN product p ON c.id = p.category_id WHERE p.id = :productId";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->bindParam(':productId', $productId);
                                                $stmt->execute();
                                                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                foreach ($categories as $category) {
                                                    $categoryId = htmlspecialchars($category['id']);
                                                    $categoryName = htmlspecialchars($category['name']);
                                                    ?>
                                                    <span>Danh mục: </span><?php echo $categoryName ?>
                                                    <?php
                                                }
                                                ?>
                                            </h5>
                                        <img src="img/shop-details/details-payment.png" alt="">


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12" style="margin-bottom: 50px">
                                <div class="product__details__tab">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">

                                        </li>
                                        <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#tabs-5" role="tab">Mô tả</a>
                                        </li>
                                        <li class="nav-item">

                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tabs-5" role="tabpanel">
                                            <div class="product__details__tab__content">

                                                <div class="product__details__tab__content__item">
                                                    <h5>Thông tin sản phẩm</h5>
                                                    <p><?php echo $item['description'] ?></p>
                                                    <p>As is the case with any new technology product, the cost of a Pocket PC
                                                        was substantial during it’s early release. For approximately $700.00,
                                                        consumers could purchase one of top-of-the-line Pocket PCs in 2003.
                                                        These days, customers are finding that prices have become much more
                                                        reasonable now that the newness is wearing off. For approximately
                                                        $350.00, a new Pocket PC can now be purchased.</p>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <?php
                                endforeach;
    }
    ?>


    <?php
    layout('footer', $data);
    ?>

    <!-- Search Begin -->
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