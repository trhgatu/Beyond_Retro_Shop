<?php
$data = [
    'pageTitle' => 'Tài khoản',
];
require_once '../class/account.php';
require_once '../class/user.php';
$user = new User($conn);
$profileUser = $user->showProfile();
if (!isset($_SESSION['tokenlogin'])) {
    header("Location: ../user/?module=authen&action=login");
    exit;
}
if (isPost()) {
    $account = new Account($conn);
    $account->confirmPassword();
}


$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>

<?php
layout('header', $data);
?>
<div class="container" style="padding-top: 110px">
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

                        <form class="user" method="post">
                            <?php
                            if (!empty($msg)) {
                                getMSG($msg, $msg_type);
                            }
                            ?>
                            <div class="card-body">
                                <div class="row mb-3" style="align-items: center">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0" style="font-weight: bold">Mật khẩu</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary d-flex justify-content-between">
                                        <input type="password" class="form-control" name="password"
                                            placeholder="Nhập mật khẩu hiện tại để xác minh"></input>
                                    </div>
                                </div>
                                <div class="row-btn">
                                    <div class="row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-9 text-secondary">
                                            <button class="btn btn-primary form-control" type="submit">Xác nhận</button>
                                        </div>
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
        font-size: 15px;
    }

    a:hover {
        color: #e53637;
    }

</style>