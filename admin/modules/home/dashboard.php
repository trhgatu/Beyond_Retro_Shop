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
    <div id="content-wrapper" class="d-flex flex-column">
        <?php
        layout_admin('header', $data);
        layout_admin('style', $data);
        layout_admin('footer', $data);
        ?>
    </div>
    <!-- End of Content Wrapper -->
</div>