<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
$data = [
    'pageTitle' => 'Dashboard'
];
//Kiểm tra trạng thái đăng nhập
if (!isAdminLogin()) {
    redirect('?module=authen&action=login');
}


?>
<div id="wrapper">

    <!-- Sidebar -->
    <?php
    layout_admin('sidebar', $data);
    ?>

    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper">
        <?php
        layout_admin('header', $data);
        ?>
        <div class="admin-wrapper">
            <div class="admin-title">
                <marquee direction="right">
                    <h4>Chào mừng bạn đến với Hệ thống Quản trị</h4>
                </marquee>
                <marquee direction="left">
                    <h4>Được phát triển bởi Beyond Retro</h4>
                </marquee>
                <hr>
                <div class="background-admin">
                    <img src="../img/dev_background.jpg">
                </div>

            </div>

        </div>
        <?php
        layout_admin('style', $data);
        ?>

    </div>

</div>
<style>
    .background-admin img{
        width: 600px;
    }
    .admin-title {
        text-align: center;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        transition: 0.3s;
        color: #333;
        font-size: 20px;
    }

    .admin-title h2 {
        margin: 10px 0;
    }

    .admin-title:hover {
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
    }

    @keyframes colorChange {
        0% {
            color: #1abc9c;
        }

        25% {
            color: #3498db;
        }

        50% {
            color: #9b59b6;
        }

        75% {
            color: #e74c3c;
        }

        100% {
            color: #1abc9c;
        }
    }

    .admin-title {
        text-align: center;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        transition: 0.3s;
        font-size: 20px;
        animation: colorChange 5s infinite;
    }

    .admin-title h2 {
        margin: 10px 0;
        animation: colorChange 5s infinite;
    }

    .admin-title:hover {
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
    }

    .marquee {
        font-size: 24px;
        font-weight: bold;
        color: #3498db;
        text-shadow: 2px 2px 4px #000000;
    }
</style>