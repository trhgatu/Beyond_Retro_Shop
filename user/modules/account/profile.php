<?php
$data = [
    'pageTitle' => 'Tài khoản',
];
require_once '../class/user.php';
require_once '../class/account.php';
$user = new User($conn);
$account = new Account($conn);

if (!isUserLogin()) {
    redirect('../user/?module=authen&action=login');
}
if (isPost()) {
    $account->updateInfo();
}
$profileUser = $user->showProfile();

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$error = getFlashData('error');
$old = getFlashData('old');
?>

<?php
layout('header', $data);
?>
<div class="container" style="padding-top: 130px">
    <div class="main-body">
        <div class="row">
            <div class="col-lg-4" style="padding: 0">
                <?php
                if (!empty($profileUser)):
                    include "card.php";
                    ?>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <?php
                        if (!empty($msg)) {
                            getMSG($msg, $msg_type);
                        }
                        ?>
                        <form class="user" method="post">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-9 text-secondary">
                                        <div class="col-sm-3 ">
                                            <h6 class="mb-0">Ảnh đại diện</h6>
                                        </div>
                                        <div class="avatar" style="padding-bottom: 15px">
                                            <img src="../images/avatar/<?php echo $profileUser['avatar'] ?>" alt="Admin"
                                                class="rounded-circle p-1 bg-primary" width="110" id="ShowImage"
                                                style="max-width: 110px; max-height:110px;">
                                        </div>
                                        <div class="avatar-input">
                                            <input type="file" id="avatar" name="avatar" onchange="readURL(this);">

                                            <label for="avatar" class="avatar-input-label">Chọn ảnh</label>
                                        </div>
                                        <style>
                                            #ShowImage {
                                                box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
                                                width: 110px;
                                                height: 110px;
                                                max-width: 100%;

                                            }
                                        </style>
                                        <script>
                                            function readURL(input) {
                                                if(input.files && input.files[0]) {
                                                    var reader = new FileReader();
                                                    reader.onload = function (e) {
                                                        $('#ShowImage')
                                                            .attr('src', e.target.result)
                                                            .width(150)
                                                            .height(200);
                                                    };
                                                    reader.readAsDataURL(input.files[0]);
                                                }
                                            }
                                        </script>
                                    </div>
                                </div>
                                <style>
                                    .avatar-input {
                                        display: inline-block;
                                        position: relative;
                                    }

                                    .avatar-input input[type="file"] {
                                        position: absolute;
                                        top: 0;
                                        left: 0;
                                        width: 100%;
                                        height: 100%;
                                        opacity: 0;
                                        cursor: pointer;
                                    }

                                    .avatar-input-label {
                                        display: inline-block;
                                        padding: 10px 20px;
                                        border: 2px solid #ccc;
                                        background-color: #f9f9f9;
                                        cursor: pointer;
                                        text-align: center;
                                    }

                                    .avatar-input-label:hover {
                                        background-color: #e3e3e3;
                                    }
                                </style>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Họ tên</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" class="form-control"
                                            value="<?php echo $profileUser['fullname'] ?>" name="fullname">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Email</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary d-flex justify-content-between">
                                        <p><b><?php echo $profileUser['email'] ?></b></p><a href="#">Thay đổi</a>
                                    </div>

                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Số điện thoại</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary d-flex justify-content-between">
                                        <p><b><?php echo $profileUser['phone_number'] ?></b></p><a
                                            href="?module=account&action=phone">Thay đổi</a>
                                    </div>
                                </div>

                                <?php
                                $userId = getSession('user_id');
                                $listAddress = oneRaw("SELECT * FROM addresses WHERE user_id = $userId");

                                ?>
                                <?php
                                if (!empty($listAddress)) {
                                    ?>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Địa chỉ</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary d-flex justify-content-between">
                                            <p><b><?php echo $listAddress['address'] . ', ' . $listAddress['district'] . ', </br>' . $listAddress['city'] . ', ' . $listAddress['country'] ?></b>
                                            </p><a href="?module=address&action=list">Cập nhật</a>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>

                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <button class="btn btn-primary px-4" type="submit">Lưu</button>
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