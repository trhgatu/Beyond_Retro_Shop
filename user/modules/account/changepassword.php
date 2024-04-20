<?php
$data = [
    'pageTitle' => 'Tài khoản',
];
require_once '../class/account.php';
require_once '../class/user.php';

$user = new User($conn);
$account = new Account($conn);

$profileUser = $user->showProfile();
if (!isset($_SESSION['tokenlogin'])) {
    header("Location: ../user/?module=authen&action=login");
    exit;
}
if (!isUserLogin()) {
    redirect('?module=authen&action=login');
}

if (isPost()) {
    $account->changePassword();
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$error = getFlashData('error');
?>

<?php
layout('header', $data);
?>
<div class="container" style="padding-top: 130px">
    <div class="main-body">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <?php
                        if (!empty($profileUser)):
                            ?>
                            <div class="d-flex flex-column align-items-center text-center">
                                <img src="../images/avatar/<?php echo $profileUser['avatar'] ?>" alt="Admin"
                                    class="rounded-circle p-1 bg-primary" width="110">

                                <div class="mt-3">
                                    <h4>
                                        <?php echo $profileUser['fullname'] ?>
                                    </h4>
                                    <p class="text-muted font-size-sm">
                                        <?php echo $profileUser['address'] ?>
                                    </p>

                                </div>
                            </div>
                            <hr class="my-4">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item align-items-center flex-wrap">

                                    <h6 class="mb-0">
                                        <a href="?module=account&action=changepassword">
                                            <img src="../img/password.png" style="width: 25px; height: 25px">
                                            Đổi mật khẩu
                                        </a>
                                    </h6>


                                </li>
                                <li class="list-group-item  align-items-center flex-wrap">

                                    <h6 class="mb-0">
                                        <a href="#">
                                            <img src="https://down-vn.img.susercontent.com/file/f0049e9df4e536bc3e7f140d071e9078"
                                                style="width: 25px; height: 25px">
                                            Đơn mua
                                        </a>
                                    </h6>


                                </li>
                                <li class="list-group-item  align-items-center flex-wrap">

                                    <h6 class="mb-0">
                                        <a href="?module=authen&action=logout" class="btn btn-primary btn-danger">
                                            Đăng xuất
                                        </a>
                                    </h6>


                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">

                        <form class="user" method="post">
                            <?php
                            if (!empty($msg)) {
                                getMSG($msg, $msg_type);
                            }
                            ?>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Mật khẩu mới</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary d-flex justify-content-between">
                                        <input type="text" class="form-control" name="password"
                                            placeholder="Nhập mật khẩu mới"></input>
                                    </div>
                                    <?php
                                    echo form_error('password', '<span class= "error">', '</span>', $error);

                                    ?>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Nhập lại mật khẩu</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary d-flex justify-content-between">
                                        <input type="text" class="form-control" name="password_confirm"
                                            placeholder="Nhập lại mật khẩu mới"></input>
                                    </div>
                                    <?php
                                    echo form_error('password_confirm', '<span class= "error">', '</span>', $error);

                                    ?>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <button class="form-control" type="submit">Xác nhận</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <?php

                        endif;
                        ?>

<?php
layout('footer', $data);
?>

<style>
    .card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 0 solid transparent;
        border-radius: .25rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 6px 0 rgb(218 218 253 / 65%), 0 2px 6px 0 rgb(206 206 238 / 54%);
    }

    .me-2 {
        margin-right: .5rem !important;
    }

    a {
        color: #000000;
    }

    a:hover {
        color: #e53637;
    }
</style>