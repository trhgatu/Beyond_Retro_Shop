<?php
$data = [
    'pageTitle' => 'Tài khoản',
];
require_once '../class/address.php';
require_once '../class/user.php';
$user = new User($conn);
$profileUser = $user->showProfile();

if (isPost()) {
    $address = new Address($conn);
    $address->addAddress();
}


$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
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
                    include "modules/account/card.php";
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
                                <div class="row mb-3">
                                    <div class="col-sm-2">
                                        <h6 class="mb-0">Địa chỉ</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary d-flex justify-content-between">
                                        <input type="text" class="form-control" name="address"
                                            placeholder="Số nhà, tên đường"></input>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-2">
                                        <h6 class="mb-0">Quận</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary d-flex justify-content-between">
                                        <input type="text" class="form-control" name="district"
                                            placeholder="Vd: Tân Phú"></input>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-2">
                                        <h6 class="mb-0">Thành phố</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary d-flex justify-content-between">
                                        <input type="text" class="form-control" name="city"
                                            placeholder="Vd: TP.HCM"></input>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-2">
                                        <h6 class="mb-0">Quốc gia</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary d-flex justify-content-between">
                                        <input type="text" class="form-control" name="country"
                                            placeholder="Vd: Việt Nam"></input>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <div class="btn-submit">
                                        <button class="btn btn-primary" type="submit">Xác nhận</button>
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
    }

    a:hover {
        color: #e53637;
    }

    .row.mb-3 {
        padding: 4px;
    }
    .btn-submit{
        padding-top: 20px;
    }
</style>