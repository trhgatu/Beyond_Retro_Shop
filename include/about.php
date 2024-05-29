<?php
session_start();
include "../user/config.php";
require_once ("../db_function/connect.php");
require_once ("../db_function/functions.php");
require_once ("../db_function/database.php");
require_once ("../db_function/session.php");
require_once '../class/product.php';
require_once '../class/category.php';
$data = [
    'pageTitle' => 'Giới thiệu'
];
//Kiểm tra trạng thái đăng nhập
if (!isUserLogin()) {
    redirect('../user/?module=authen&action=login');
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
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Giới thiệu</h4>
                        <div class="breadcrumb__links">
                            <a href="<?php echo BASE_URL; ?>index.php">Home</a>
                            <span>Giới thiệu</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- About Section Begin -->
    <section class="about spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="about__pic">
                        <img src="../img/about/about-us.jpg" alt="">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="about__item">
                        <h4>Về dự án</h4>
                        <p>Đây là một website bán quần áo second-hand có tên là Beyond Retro.</p>
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="about__item">

                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="about__item">
                        <h4>Định hướng</h4>
                        <p>Hoàn thành Đồ án môn học Lập trình Mã nguồn mở bằng ngôn ngữ lập trình PHP.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About Section End -->

    <!-- Testimonial Section Begin -->
    <section class="testimonial">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 p-0">
                    <div class="testimonial__text">
                        <span class="icon_quotations"></span>
                        <p>“Đi nhanh cũng được, chậm cũng được, quan trọng là đừng trễ deadline.”
                        </p>
                        <div class="testimonial__author">
                            <div class="testimonial__author__pic">
                                <img src="../img/about/me.png" alt="">
                            </div>
                            <div class="testimonial__author__text">
                                <h5>Trần Hoàng Anh Tú</h5>
                                <p>Developer</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 p-0">
                    <img class="testimonial__pic set-bg" src="../img/about/testimonial-pic.jpg">

                </div>
            </div>
        </div>
    </section>

    <section class="team spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Thông tin sinh viên</span>
                        <h2>Sinh viên thực hiện</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="team__item">
                        <img src="../img/about/me.png" alt="">
                        <h4>Trần Hoàng Anh Tú</h4>
                        <span>Mã số sinh viên: 2001210084</span>
                        <span>Sinh viên trường Đại học Công thương Thành Phố Hồ Chí Minh</span>

                    </div>
                </div>


            </div>
        </div>
    </section>


    <!-- Footer Section Begin -->
    <?php
    layout('footer', $data);
    ?>
    <!-- Footer Section End -->

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