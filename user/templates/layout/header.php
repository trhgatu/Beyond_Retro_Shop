<!DOCTYPE html>
<html lang="zxx">
<?php
$current_url = $_SERVER['REQUEST_URI'];

function echoActiveClass($requestUri)
{
    $current_file_name = basename($_SERVER['PHP_SELF'], ".php");
    if ($current_file_name == $requestUri) {
        echo 'class="active"';
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Male_Fashion Template">
    <meta name="keywords" content="Male_Fashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        <?php echo !empty($data['pageTitle']) ? $data['pageTitle'] : 'Beyond Retro'; ?>
    </title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>/css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>/css/style.css" type="text/css">

    <!-- JS -->
    <script src="<?php echo _WEB_HOST_TEMPLATE; ?>/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE; ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE; ?>/js/jquery.nice-select.min.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE; ?>/js/jquery.nicescroll.min.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE; ?>/js/jquery.magnific-popup.min.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE; ?>/js/jquery.countdown.min.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE; ?>/js/jquery.slicknav.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE; ?>/js/mixitup.min.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE; ?>/js/owl.carousel.min.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE; ?>/js/main.js"></script>
</head>
<!-- Header Section Begin -->
<header class="header"
    style="position: fixed; z-index: 9999;width: 100%; box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class="header__logo">
                    <a href="<?php echo BASE_URL; ?>index.php"><img src="../img/beyond-retro-logo.png"
                            style="max-width : 80%" alt=""></a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <nav class="header__menu mobile-menu">
                    <ul>
                        <li><a href="<?php echo BASE_URL; ?>index.php" <?php echoActiveClass("index"); ?>>Home</a></li>
                        <li><a href="<?php echo BASE_URL; ?>shop.php?page=1" <?php echoActiveClass("shop"); ?>>Shop</a></li>
                        <li><a href="<?php echo BASE_URL; ?>about.php" <?php echoActiveClass("about"); ?>>Giới thiệu</a></li>
                        <li><a href="<?php echo BASE_URL; ?>contact.php" <?php echoActiveClass("contact"); ?>>Liên hệ</a></li>
                    </ul>
                </nav>
            </div>
            <style>
                .header__menu.mobile-menu ul li a.active {
                    border-bottom: 2px solid #e53637;
                    color: #000;
                }
            </style>
            <div class="col-lg-3 col-md-3">
                <div class="header__nav__option">
                    <a href="#" class="search-switch"><img src="../img/icon/search-icon.png" alt=""
                            style="width: 20px"></a>
                    <?php
                    if (isUserLogin()) {
                        ?>
                        <a href="../user/?module=account&action=profile" class="account-switch"><img
                                src="../img/icon/user.png" alt="" style="width: 20px"></a>
                        <?php
                    } else {
                        ?>
                        <a href="../user/?module=authen&action=login" class="account-switch"><img src="../img/icon/user.png"
                                alt="" style="width: 20px"></a>
                        <?php
                    }
                    ?>
                    <?php
                    $cartCount = isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0;
                    ?>
                    <a href="shopping-cart.php" class="cart-switch">
                        <img src="../img/icon/shopping-bag.png" style="width: 23px" alt="">
                        <span id="cart-count"><?php echo $cartCount ?></span>
                    </a>


                </div>
            </div>
            <div class="canvas__open"><i class="fa fa-bars"></i></div>
        </div>
</header>
<?php
layout('search', $data)
    ?>