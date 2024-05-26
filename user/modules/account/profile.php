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
    $filterAll = filter();
    $error = [];
    $avatarPath = null; // Khởi tạo avatarPath là null

    if (empty($error)) {
        try {
            if (!empty($_FILES['avatar']) && $_FILES['avatar']['error'] != UPLOAD_ERR_NO_FILE) {
                switch ($_FILES['avatar']['error']) {
                    case UPLOAD_ERR_OK:
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        throw new Exception('Không có tệp nào được tải lên');
                    default:
                        throw new Exception('Đã xảy ra lỗi');
                }
                if ($_FILES['avatar']['size'] > 1000000) {
                    throw new Exception('Tệp quá lớn');
                }
                $mime_types = ['image/png', 'image/jpeg', 'image/gif'];
                $file_info = finfo_open(FILEINFO_MIME_TYPE);
                $mime_type = finfo_file($file_info, $_FILES['avatar']['tmp_name']);

                if (!in_array($mime_type, $mime_types)) {
                    throw new Exception('Loại tệp không hợp lệ');
                }
                $pathinfo = pathinfo($_FILES['avatar']['name']);
                $fname = 'avatar';
                $extension = $pathinfo['extension'];
                $dest = '../images/avatar/' . $fname . '.' . $extension;
                $i = 1;
                while (file_exists($dest)) {
                    $dest = '../images/avatar/' . $fname . "-$i." . $extension;
                    $i++;
                }
                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $dest)) {
                    $avatarPath = $dest;
                } else {
                    throw new Exception('Không thể di chuyển tệp');
                }
            }

            $account->updateInfo($filterAll, $avatarPath);

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    } else {
        setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu');
        setFlashData('msg_type', 'danger');
        setFlashData('error', $error);
        setFlashData('old', $filterAll);
        redirect('?module=account&action=profile');
    }
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
                    include "card.php";
                    ?>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <form class="user" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <?php
                                if (!empty($msg)) {
                                    getMSG($msg, $msg_type);
                                }
                                ?>
                                <div class="profile-title">
                                    <div class="profile-title-h5">
                                        <h5>Hồ sơ của tôi</h5>
                                    </div>
                                    <div class="profile-des">
                                        <p style="color: rgba(85, 85, 85, .8);">Quản lý thông tin hồ sơ để bảo mật tài
                                            khoản.</p>
                                    </div>
                                </div>
                                <div class="profile-information">
                                    <div class="card-body-avatar">
                                        <div class="avatar" style="padding-bottom: 15px">
                                            <img src="../images/avatar/<?php echo $profileUser['avatar'] ?>" alt="Admin"
                                                class="rounded-circle p-1 bg-primary" width="110" id="ShowImage"
                                                style="max-width: 110px; max-height:110px;">
                                        </div>
                                        <div class="avatar-input">
                                            <input type="file" name="avatar" onchange="readURL(this);">

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
                                    <div class="card-body-information">
                                        <table>
                                            <tr>
                                                <td style="color: rgba(85, 85, 85, .8);">Họ tên</td>
                                                <td><input type="text" class="form-control"
                                                        value="<?php echo $profileUser['fullname'] ?>" name="fullname"></td>
                                            </tr>
                                            <tr>
                                                <td style="color: rgba(85, 85, 85, .8);">Email</td>
                                                <td><?php echo $profileUser['email'] ?></td>
                                                <style>
                                                    td {
                                                        padding-bottom: 25px;
                                                        padding-right: 25px;
                                                        font-size: 15px;
                                                    }
                                                </style>
                                            </tr>
                                            <tr>
                                                <td style="color: rgba(85, 85, 85, .8);">Số điện thoại</td>
                                                <td><?php echo $profileUser['phone_number'] ?></td>
                                            </tr>
                                            <?php
                                            $userId = getSession('user_id');
                                            $listAddress = oneRaw("SELECT * FROM addresses WHERE user_id = $userId AND is_default = 1");
                                            ?>
                                            <?php
                                            if (!empty($listAddress)) {
                                                ?>
                                                <tr>
                                                    <td style="color: rgba(85, 85, 85, .8);">Địa chỉ</td>
                                                    <td><?php echo $listAddress['address'] . ', ' . $listAddress['district'] . ', </br>' . $listAddress['city'] . ', ' . $listAddress['country'] ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>

                                        </table>

                                        <div class="row">
                                            <div class="col-sm-9 text-secondary justify-content-between"
                                                style="padding-left: 20px">
                                                <button class="btn btn-primary px-4" type="submit">Lưu</button>
                                            </div>
                                        </div>
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
    .profile-information {
        display: flex;
    }

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

    .row.mb-3 {
        padding: 4px;
    }
    .card-body-information {
        padding-left: 25px;
    }

    .card-body-avatar {
        padding-left: 15px;
        padding-right: 30px;
        border-right: 1px solid #dee2e6;
    }

    .profile-title {
        border-bottom: 1px solid #dee2e6;
    }

    .profile-information {
        padding-top: 20px;
    }
</style>