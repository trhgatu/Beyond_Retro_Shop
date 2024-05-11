<?php
$data = [
    'pageTitle' => 'Tài khoản',
];
require_once '../class/user.php';
require_once '../class/account.php';
$user = new User($conn);
$account = new Account($conn);
if (!isset($_SESSION['tokenlogin'])) {
    header("Location: ../user/?module=authen&action=login");
    exit;
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
                    include "modules/account/card.php";
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

                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Địa chỉ của tôi</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary d-flex justify-content-between">
                                        <p></p><a href="?module=address&action=add" class="btn btn-danger"
                                            style="background-color:#e53637 ">Thêm địa chỉ mới</a>
                                    </div>
                                </div>
                                <?php
                                $userId = getSession('user_id');
                                $listAddress = getRaw("SELECT * FROM addresses WHERE user_id = $userId");
                                ?>
                                <?php
                                if (!empty($listAddress)) {
                                    foreach ($listAddress as $list):
                                        ?>
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <p style="font-weight : bold"><?php echo $profileUser['fullname'] ?>
                                                    </br><?php echo $profileUser['phone_number'] ?></br>
                                                </p>
                                            </div>
                                            <div class="col-sm-9 text-secondary d-flex justify-content-between">
                                                <p><b><?php echo $list['address'] . ', ' . $list['district'] . ', </br>' . $list['city'] . ', ' . $list['country'] ?></b>
                                                    <?php
                                                    if ($list['is_default'] == 1) {
                                                        echo '<br><span style="color: green;">Địa chỉ mặc định</span>';
                                                    } else {
                                                        echo '<a href="?module=address&action=setdefault&id=' . $list['id'] . '">Đặt làm mặc định</a>';
                                                    }
                                                    ?>
                                                </p>
                                                <a href="?module=address&action=delete&id=<?php echo $list['id'] ?>"
                                                    onclick="return confirm('Bạn có muốn xóa địa chỉ này?')">Xóa</td></a>
                                                <a href="?module=address&action=edit&id=<?php echo $list['id'] ?>">Cập nhật</a>

                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-5">

                                            </div>
                                            <div class="col-sm-7 text-secondary d-flex justify-content-between">

                                            </div>
                                        </div>
                                        <?php
                                    endforeach;
                                }
                                ?>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <?php

                endif;
                ?>
</div>
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