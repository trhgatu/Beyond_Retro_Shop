<?php
$data = [
    'pageTitle' => 'Tài khoản',
];
require_once '../class/admin.php';
require_once '../class/account.php';
$admin = new Admin($conn);
$account = new Account($conn);

if (!isAdminLogin()) {
    redirect('?module=authen&action=login');
}
if (isPost()) {
    $filterAll = filter();
    $error = [];
    $avatarAdminPath = null;

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
                    $avatarAdminPath = $dest;
                } else {
                    throw new Exception('Không thể di chuyển tệp');
                }
            }
            $account->updateInfoAdmin($filterAll, $avatarAdminPath);

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
$profileAdmin = $admin->show();

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$error = getFlashData('error');
$old = getFlashData('old');
?>
<div id="wrapper">
    <?php
    layout_admin('style', $data);
    layout_admin('sidebar', $data);
    ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php layout_admin('header', $data); ?>
            <div class="container-fluid" style="width: 1000px  ">
                <div class="card o-hidden border-0 shadow-lg">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div>
                            <div class="profile-title">
                                <div class="profile-title-text">
                                    <div class="text-left">
                                        <h1 class="h4 text-white" style="text-transform: uppercase">Hồ sơ admin</h1>
                                    </div>
                                    <div class="provider">
                                    </div>
                                    <div class="text-right">
                                        <h1 class="h6 text-white">Quản lý thông tin tài khoản</h1>
                                    </div>
                                </div>

                            </div>
                            <style>
                                .profile-wrapper {
                                    padding: 20px 80px;
                                }

                                .profile-title {
                                    background-color: #CC181E;
                                    height: auto;
                                }

                                .provider {
                                    height: 36px;
                                    width: 1px;
                                    margin-left: 20px;
                                    border: 1px solid #ffffff;
                                }

                                .profile-title-text {
                                    padding: 15px;
                                    display: flex;
                                    align-items: center;
                                }

                                .profile-information {
                                    padding: 20px;

                                }

                                .text-right {
                                    margin-left: 25px;
                                }

                                .text-left {
                                    margin-left: 132px;
                                }

                                .avatar-input {
                                    margin-left: 50px;
                                }

                                .avatar-image {
                                    margin-left: 50px;
                                }

                                .avatar-input input[type="file"] {
                                    display: none;
                                }


                                .avatar-input label {
                                    display: inline-block;
                                    padding: 10px 36px;
                                    background-color: #CC181E;
                                    color: white;
                                    border-radius: 5px;
                                    cursor: pointer;
                                    transition: background-color 0.3s;
                                }

                                .avatar-input label:hover {
                                    background-color: #0056b3;
                                }

                                /* Định dạng cho các input của form */
                                .form-control-user {
                                    border: 2px solid #ced4da;
                                    border-radius: 4px;
                                    padding: 10px;
                                    font-size: 1rem;
                                    transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
                                }

                                /* Định dạng cho các input khi người dùng tập trung vào */
                                .form-control-user:focus {
                                    border-color: #007bff;
                                    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
                                }

                                /* Định dạng cho các nút */
                                .btn {
                                    padding: 10px 20px;
                                    font-size: 1rem;
                                    border-radius: 4px;
                                    transition: background-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
                                }

                                /* Định dạng cho nút khi người dùng di chuột vào */
                                .btn:hover {
                                    background-color: #0056b3;
                                    box-shadow: 0 0 5px rgba(0, 86, 179, 0.5);
                                }

                                /* Định dạng cho nút submit */
                                .btn-primary {
                                    background-color: #007bff;
                                    border-color: #007bff;
                                }

                                /* Định dạng cho nút quay lại */
                                .btn-success {
                                    background-color: #28a745;
                                    border-color: #28a745;
                                }

                                /* Định dạng lỗi */
                                .error {
                                    color: red;
                                    font-size: 0.875rem;
                                    margin-top: 5px;
                                }
                            </style>

                            <?php if (!empty($msg)) {
                                getMSG($msg, $msg_type);
                            } ?>
                            <?php
                            if (!empty($profileAdmin)):
                                ?>
                                <form method="post" enctype="multipart/form-data">
                                    <div class="profile-wrapper">
                                        <div class="profile-information">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <div class="avatar" style="padding-bottom: 15px">
                                                            <div class="avatar-image">
                                                                <img src="../images/avatar/<?php echo $profileAdmin['avatar'] ?>"
                                                                    alt="Admin" class="rounded p-1 bg-primary" width="110"
                                                                    id="ShowImage">
                                                            </div>
                                                        </div>
                                                        <div class="avatar-input">
                                                            <label for="avatarInput">Chọn ảnh đại diện</label>
                                                            <input type="file" name="avatar" id="avatarInput"
                                                                onchange="readURL(this);">
                                                        </div>

                                                        <style>
                                                            #ShowImage {
                                                                box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
                                                                width: 200px;
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

                                                <div class="col">

                                                    <div class="form-group">
                                                        <input type="fullname" class="form-control form-control-user"
                                                            id="exampleInputEmail" placeholder="Họ tên" name="fullname"
                                                            value="<?php echo $profileAdmin['fullname'] ?>">
                                                        <?php echo form_error('fullname', '<span class= "error">', '</span>', $error); ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="email" class="form-control form-control-user"
                                                            id="exampleInputEmail" placeholder="Địa chỉ email" name="email"
                                                            value="<?php echo $profileAdmin['email'] ?>">
                                                        <?php echo form_error('email', '<span class= "error">', '</span>', $error); ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control form-control-user"
                                                            id="exampleInputPassword" placeholder="Số điện thoại"
                                                            name="phone_number"
                                                            value="<?php echo $profileAdmin['phone_number'] ?>">
                                                        <?php echo form_error('phone_number', '<span class= "error">', '</span>', $error); ?>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                                            <button type="submit"
                                                                class="mg-btn btn btn-primary btn-block">Cập nhật
                                                            </button>
                                                        </div>
                                                        <div class="col-sm-6"><a href="?module=home&action=dashboard"
                                                                class="mg-btn btn btn-success btn-block">Quay lại</a></div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                            </div>
                            </form>
                            <?php
                            endif;
                            ?>

                    </div>


                </div>
            </div>
        </div>
    </div>
    <?php
    layout_admin('footer', $data);
    ?>
</div>

</div>