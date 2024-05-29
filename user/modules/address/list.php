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
<div class="container" style="padding-top: 110px">
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

                            <div class="card-body" style="padding-bottom: 0">
                                <?php
                                if (!empty($msg)) {
                                    getMSG($msg, $msg_type);
                                }
                                ?>
                                <div class="row mb-4" style="align-items: center">
                                    <div class="col-sm-3">
                                        <h5>Địa chỉ của tôi</h5>
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

                                                </p>
                                                <p>
                                                    <?php echo $profileUser['phone_number'] ?>
                                                </p>
                                            </div>
                                            <div class="col-sm-9 text-secondary d-flex justify-content-between">
                                                <p style="    color: rgba(0, 0, 0, .54);">
                                                    <?php echo $list['address'] . ', ' . $list['district'] . ', </br>' . $list['city'] . ', ' . $list['country'] ?>
                                                    <?php
                                                    if ($list['is_default'] == 1) {
                                                        echo '<br><span style="color: red; border: 2px solid red; padding: 2px;">Mặc định</span>';
                                                    } else {
                                                        echo '<br><span style="color: rgba(0, 0, 0, .54); border: 2px solid rgba(0, 0, 0, .54); padding: 2px;"><a style="color: rgba(0, 0, 0, .54);" href="?module=address&action=setdefault&id=' . $list['id'] . '">Thiết lập mặc định</a></span>';
                                                    }
                                                    ?>
                                                </p>
                                                <div class="action-link" style="display: flex;">
                                                    <div class="action-link-delete">
                                                        <a href="?module=address&action=delete&id=<?php echo $list['id'] ?>"
                                                            onclick="return confirm('Bạn có muốn xóa địa chỉ này?')">Xóa</td></a>
                                                    </div>

                                                </div>


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
        border-radius: 2px;
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

    .row.mb-3 {
        border-bottom: 1px solid #efefef;
        padding-top: 10px;
    }

    .row.mb-4 {
        border-bottom: 1px solid #efefef;
        padding: 10px;
    }

    .action-link .action-link-delete {
        padding-right: 15px;
    }
</style>