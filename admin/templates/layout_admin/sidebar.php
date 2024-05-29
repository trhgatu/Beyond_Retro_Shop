<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
?>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient sidebar sidebar-dark accordion" id="accordionSidebar"
    style="background-color: #CC181E; ">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="?module=home&action=dashboard"
        style="margin: 15px">
        <div class="sidebar-brand-text mx-3" style="font-family: sans-serif; font-size: 20"> <img
                src="../img/settings.png" style="width: 50px">ADMINISTRATOR</div>
    </a>


    <!-- Divider -->

    <hr class="sidebar-divider d-none d-md-block">
    <li class="nav-item active">
        <a class="nav-link" href="?module=home&action=dashboard">
            <?php
            echo '<i class="fas fa-fw fa-tachometer-alt"></i>';
            ?>
            <span>Dashboard</span>
        </a>
    </li>
    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>
    <!-- Nav Item - Users -->

    <li class="nav-item">
        <a class="nav-link" href="?module=users&action=list">
            <i class="fa-solid fa-user"></i>
            <span>Quản lý người dùng</span>
        </a>
    </li>
    <!-- Nav Item - Category -->
    <li class="nav-item">
        <a class="nav-link" href="?module=category&action=list">
            <i class="fa-solid fa-list"></i>
            <span>Quản lý danh mục</span>
        </a>
    </li>
    <!-- Nav Item - Products -->
    <li class="nav-item">
        <a class="nav-link" href="?module=products&action=list">
            <i class="fa-solid fa-shop"></i>
            <span>Quản lý sản phẩm</span>
        </a>
    </li><!-- Nav Item - Orders -->
    <li class="nav-item">
        <a class="nav-link" href="?module=orders&action=list">
            <i class="fa-solid fa-newspaper"></i>
            <span>Quản lý đơn hàng</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="?module=feedback&action=list">
            <i class="fa-regular fa-envelope"></i>
            <span>Hộp thư góp ý</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">
    <?php
    layout_admin('footer', $data);
    ?>
</ul>
<!-- End of Sidebar -->