<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
?>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="?module=home&action=dashboard">
        <div class="sidebar-brand-icon rotate-n-15">
            <?php

            echo '<i class="fas fa-laugh-wink"></i>';
            ?>
        </div>
        <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="?module=home&action=dashboard">
            <?php
            echo '<i class="fas fa-fw fa-tachometer-alt"></i>';
            ?>

            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

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

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Components</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Components:</h6>
                <a class="collapse-item" href="buttons.html">Buttons</a>
                <a class="collapse-item" href="cards.html">Cards</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Utilities</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Utilities:</h6>
                <a class="collapse-item" href="utilities-color.html">Colors</a>
                <a class="collapse-item" href="utilities-border.html">Borders</a>
                <a class="collapse-item" href="utilities-animation.html">Animations</a>
                <a class="collapse-item" href="utilities-other.html">Other</a>
            </div>
        </div>
    </li>
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->

</ul>
<!-- End of Sidebar -->