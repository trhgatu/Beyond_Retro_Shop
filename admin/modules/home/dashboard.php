<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
$data = [
    'pageTitle' => 'Dashboard'
];
//Kiểm tra trạng thái đăng nhập

?>
<div id="wrapper">

    <!-- Sidebar -->
    <?php
    layouts('sidebar', $data);
    ?>

    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <?php
        layouts('header', $data);
        layouts('style', $data);
        layouts('footer', $data);
        ?>
    </div>
    <!-- End of Content Wrapper -->
</div>