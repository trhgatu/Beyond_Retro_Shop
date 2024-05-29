<?php
session_start();
include "../user/config.php";
require_once ("../db_function/connect.php");
require_once ("../db_function/functions.php");
require_once ("../db_function/database.php");
require_once ("../db_function/session.php");
require_once '../class/product.php';
require_once '../class/category.php';
require_once '../class/feedback.php';
$data = [
    'pageTitle' => 'Liên hệ'
];
//Kiểm tra trạng thái đăng nhập
if (!isUserLogin()) {
    redirect('../user/?module=authen&action=login');
}
if(isPost()){
    $feedback = new Feedback($conn);
    $feedback->sendFeedback($dataSend);
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
    <!-- Page Preloder -->


    <?php
    layout('header', $data);
    ?>


    <!-- Map Begin -->
    <div class="map">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.057341470054!2d106.62883439999999!3d10.806920300000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752be27ea41e05%3A0xfa77697a39f13ab0!2zMTQwIMSQLiBMw6ogVHLhu41uZyBU4bqlbiwgVMOieSBUaOG6oW5oLCBUw6JuIFBow7osIFRow6BuaCBwaOG7kSBI4buTIENow60gTWluaA!5e0!3m2!1svi!2s!4v1705721482619!5m2!1svi!2s"
            height="500" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
    </div>
    <!-- Map End -->

    <!-- Contact Section Begin -->
    <section class="contact spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="contact__text">
                        <div class="section-title">
                            <span>Thông tin</span>
                            <h2>Liên hệ chúng tôi</h2>
                            <p>Chúng tôi luôn trân trọng và hoan nghênh mọi ý kiến đóng góp từ phía bạn. Sự phản hồi của bạn giúp chúng tôi cải thiện và phát triển dịch vụ tốt hơn. </p>
                            <br>
                            <p>Nếu bạn có bất kỳ ý kiến, đề xuất hoặc thắc mắc nào, đừng ngần ngại liên hệ với chúng tôi. Mọi góp ý của bạn đều là nguồn động lực để chúng tôi không ngừng hoàn thiện và mang đến cho bạn trải nghiệm tốt nhất.</p>
                            <br>
                            <p> Xin cảm ơn bạn đã luôn đồng hành và ủng hộ chúng tôi. Chúng tôi mong muốn nhận được phản hồi từ bạn!</p>
                        </div>
                        <ul>
                            <li>
                                <h4>Thành phố Hồ Chí Minh</h4>
                                <p>140 Lê Trọng Tấn, Tây Thạnh, Tân Phú<br />081 7070 945</p>
                            </li>

                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="contact__form">
                        <form method="post">
                            <div class="row">
                                <div class="col-lg-12">
                                    <input type="text" placeholder="Họ tên" name="fullname">
                                </div>
                                <div class="col-lg-6">
                                    <input type="text" placeholder="Email" name="email">
                                </div>
                                <div class="col-lg-6">
                                    <input type="text" placeholder="Số điện thoại" name="phone_number">
                                </div>
                                <div class="col-lg-12">
                                    <textarea placeholder="Message" name="note"></textarea>
                                    <button type="submit" class="site-btn">Gửi thư cho chúng tôi!</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Section End -->

    <!-- Footer Section Begin -->
    <?php layout('footer', $data) ?>
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