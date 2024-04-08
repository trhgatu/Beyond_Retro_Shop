<?php
$data = [
    'pageTitle' => 'Tài khoản',
];
require_once '../class/user.php';
$user = new User($conn);
if (!isset($_SESSION['tokenlogin'])) {
    header("Location: ../user/?module=authen&action=login");
    exit;
}
$profileUser = $user->showProfile();

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
                                        <a href="#">
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
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-9 text-secondary">
                                    <div class="col-sm-3 ">
                                        <h6 class="mb-0">Ảnh đại diện</h6>
                                    </div>
                                    <div class="avatar" style="padding-bottom: 15px">
                                        <img src="../images/avatar/<?php echo $profileUser['avatar'] ?>" alt="Admin"
                                            class="rounded-circle p-1 bg-primary" width="110">

                                    </div>
                                    <div class="avatar-input">
                                        <input type="file" id="avatar" name="avatar" accept="image/*">
                                        <label for="avatar" class="avatar-input-label">Chọn ảnh</label>
                                    </div>
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
                                    <input type="text" class="form-control" value="<?php echo $profileUser['fullname'] ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Email</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" class="form-control" value="<?php echo $profileUser['email'] ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Số điện thoại</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" class="form-control" value=<?php echo $profileUser['phone_number'] ?>>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Địa chỉ</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" class="form-control" value="<?php echo $profileUser['address'] ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="button" class="btn btn-primary px-4" value="Save Changes">
                                </div>
                            </div>
                        </div>
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