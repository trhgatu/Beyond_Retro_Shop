<!DOCTYPE html>
<html lang="zxx">

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
                    <a href="http://localhost/Beyond_Retro/include/index.php"><img src="../img/beyond-retro-logo.png"
                            style="max-width : 80%" alt=""></a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <nav class="header__menu mobile-menu">
                    <ul>
                        <li><a href="http://localhost/Beyond_Retro/include/index.php">Home</a></li>
                        <li><a href="http://localhost/Beyond_Retro/include/shop.php">Shop</a></li>
                        <li><a href="http://localhost/Beyond_Retro/include/about.php">Giới thiệu</a></li>
                        <li><a href="http://localhost/Beyond_Retro/include/blog.php">Blog</a></li>
                        <li><a href="http://localhost/Beyond_Retro/include/contact.php">Liên hệ</a></li>
                    </ul>
                </nav>
            </div>
            <style>
                .header__menu.mobile-menu ul li a.active {
                    border-bottom: 2px solid #e53637;
                    color: #000;
                }
            </style>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var menuItems = document.querySelectorAll('.header__menu.mobile-menu ul li a');

                    function setActiveMenuItem() {
                        var currentPagePath = window.location.pathname; // Lấy phần đường dẫn của URL hiện tại
                        var currentPageFileName = currentPagePath.split('/').pop(); // Lấy phần cuối của đường dẫn

                        menuItems.forEach(function (menuItem) {
                            var menuItemPath = menuItem.getAttribute('href');
                            var menuItemFileName = menuItemPath.split('/').pop(); // Lấy phần cuối của đường dẫn của mỗi mục menu

                            // Nếu phần cuối của đường dẫn của mục menu khớp với phần cuối của đường dẫn của URL hiện tại
                            if(currentPageFileName === menuItemFileName) {
                                menuItem.classList.add('active');
                            } else {
                                menuItem.classList.remove('active');
                            }
                        });
                    }

                    setActiveMenuItem(); // Kích hoạt một lần khi tài liệu được tải

                    window.addEventListener('popstate', setActiveMenuItem); // Kích hoạt khi trang chuyển đổi
                });



            </script>
            <div class="col-lg-3 col-md-3">
                <div class="header__nav__option">
                    <a href="#" class="search-switch"><img src="../img/icon/search-icon.png" alt=""
                            style="width: 20px"></a>
                    <?php
                    if (isLogin()) {
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
                    <a href="http://localhost/Beyond_Retro/include/shopping-cart.php"><img src="../img/icon/shopping-bag.png" style="width: 23px" alt="">
                        <span>0</span></a>
                    <div class="price">$0.00</div>
                </div>
            </div>
            <div class="canvas__open"><i class="fa fa-bars"></i></div>
        </div>
</header>
<!-- Header Section End -->
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