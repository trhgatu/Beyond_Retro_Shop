<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
require_once '../class/user.php';
$user = new User($conn);

if (!isAdminLogin()) {
    redirect('?module=authen&action=login');
}
$filterAll = filter();
if (!empty($filterAll['id'])) {
    $userId = $filterAll['id'];
    $userDetail = oneRaw("SELECT * FROM user WHERE id='$userId'");
    if (!empty($userDetail)) {
        //Tồn tại
        setFlashData('user-detail', $userDetail);
    } else {
        redirect('module=users&action=list');
    }
}
$data = [
    'pageTitle' => 'Sửa'
];
if (isPost()) {
    $filterAll = filter();
    $error = [];//Mảng chữa lỗi
    //Validate fullname: bắt buộc phải nhập, họ tên có ít nhất 5 ký tự
    if (empty($filterAll['fullname'])) {
        $error['fullname']['required'] = 'Họ tên bắt buộc phải nhập.';
    } else {
        if (strlen($filterAll['fullname']) < 5) {
            $error['fullname']['min'] = 'Họ tên phải có ít nhất 5 ký tự.';
        }
    }
    //Validate Email: bắt buộc phải nhập, đúng định dạng email không, email đã tồn tại trong csdl chưa
    if (empty($filterAll['email'])) {
        $error['email']['required'] = 'Email bắt buộc phải nhập.';
    } else {
        $email = $filterAll['email'];
        $sql = "SELECT * FROM user WHERE email = '$email' AND id <> $userId";
        if (getRows($sql) > 0) {
            $error['email']['unique'] = 'Email đã tồn tại.';
        }
    }
    //Validate Số điện thoại: bắt buộc phải nhập, đúng định dạng không
    if (empty($filterAll['phone_number'])) {
        $error['phone_number']['required'] = 'SDT bắt buộc phải nhập.';
    } else {
        if (!isPhone($filterAll['phone_number'])) {
            $error['phone_number']['isPhone'] = 'SDT không hợp lệ.';
        }
    }
    if (!empty($filterAll['password'])) {
        //Validate password confirm: bắt buộc phải nhập, giống password
        if (empty($filterAll['password_confirm'])) {
            $error['password_confirm']['required'] = 'Bạn phải nhập lại mật khẩu.';
        } else {
            if (($filterAll['password']) != ($filterAll['password_confirm'])) {
                $error['password_confirm']['match'] = 'Mật khẩu nhập lại không đúng.';
            }
        }
    }

    if (empty($error)) {
        try {
            if (empty($_FILES['avatar'])) {
                throw new Exception('Invalid upload');
            }
            switch ($_FILES['avatar']['error']) {
                case UPLOAD_ERR_OK;
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new Exception('No file upload');
                default:
                    throw new Exception('An error occured');
            }
            if ($_FILES['avatar']['size'] > 1000000) {
                throw new Exception('File too large');
            }
            $mime_types = ['image/png', 'image/jpeg', 'image/gif'];
            $file_info = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($file_info, $_FILES['avatar']['tmp_name']);

            if (!in_array($mime_type, $mime_types)) {
                throw new Exception('invalid file type');
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
                $user->edit($filterAll, $dest);
            } else {
                throw new Exception('Unable to move file');
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    } else {
        setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu');
        setFlashData('msg_type', 'danger');
        setFlashData('error', $error);
        setFlashData('old', $filterAll);
    }
    redirect('?module=users&action=edit&id=' . $userId);
}
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$error = getFlashData('error');
$old = getFlashData('old');
$userDetails = getFlashData('user-detail');
if ($userDetails) {
    $old = $userDetails;
}
?>
<div id="wrapper">
    <?php
    layout_admin('style', $data);
    layout_admin('sidebar', $data);
    ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php
            layout_admin('header', $data);
            ?>
            <div class="container-fluid">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Cập nhật thông tin người dùng</h1>
                            </div>
                            <?php
                            if (!empty($msg)) {
                                getMSG($msg, $msg_type);
                            }
                            ?>
                            <form class="user" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col">
                                        <div class="avatar">
                                            <p>Ảnh đại diện:</p>
                                            <input type="file" name="avatar" style="padding-bottom: 20px;"
                                                onchange="readURL(this);">
                                            <?php if (!empty($old['avatar'])): ?>
                                                <div class="showImage">
                                                    <img id="ShowImage"
                                                        src="../images/avatar/<?php echo $old['avatar']; ?>">
                                                </div>
                                            <?php else: ?>
                                                <p>Không có hình ảnh bìa hiện tại.</p>
                                            <?php endif; ?>

                                        </div>
                                        <style>
                                            .avatar {
                                                margin-bottom: 20px;
                                            }
                                            #ShowImage {
                                                box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
                                                width: 50%;
                                                height: auto;
                                                padding: 22px
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
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="fullname" class="form-control form-control-user"
                                                id="exampleInputEmail" placeholder="Họ tên" name="fullname" value="<?php
                                                echo old('fullname', $old)
                                                    ?>">
                                            <?php
                                            echo form_error('fullname', '<span class= "error">', '</span>', $error);

                                            ?>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                id="exampleInputEmail" placeholder="Địa chỉ email" name="email" value="<?php
                                                echo old('email', $old)
                                                    ?>">
                                            <?php
                                            echo form_error('email', '<span class= "error">', '</span>', $error);

                                            ?>
                                        </div>


                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <input type="text" class="form-control form-control-user"
                                                    id="exampleInputPassword" placeholder="Mật khẩu mới"
                                                    name="password">
                                                <?php
                                                echo form_error('password', '<span class= "error">', '</span>', $error);

                                                ?>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-control-user"
                                                    id="exampleRepeatPassword" placeholder="Nhập lại mật khẩu mới"
                                                    name="password_confirm" value="<?php
                                                    echo old('password_confirm', $old)
                                                        ?>">
                                                <?php
                                                echo form_error('password_confirm', '<span class= "error">', '</span>', $error);

                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Số điện thoại"
                                                name="phone_number" value="<?php
                                                echo old('phone_number', $old)
                                                    ?>">
                                            <?php
                                            echo form_error('phone_number', '<span class= "error">', '</span>', $error);
                                            ?>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <label for="role">Loại tài khoản:</label>
                                                <select id="role_id" name="role_id" class="form-control">
                                                    <?php
                                                    $sql = "SELECT id, name FROM role";
                                                    $stmt = $conn->prepare($sql);
                                                    $stmt->execute();
                                                    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($roles as $role) {
                                                        echo "<option value='" . $role['id'] . "'>" . $role['name'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <label for="">Trạng thái</label>
                                                <select name="status" id="" class="form-control">
                                                    <option value="0" <?php echo (old('status', $old) == 0) ? 'selected' : false; ?>>Chưa kích hoạt</option>
                                                    <option value="1" <?php echo (old('status', $old) == 1) ? 'selected' : false; ?>>Đã kích hoạt</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <input type="hidden" name="id" value="<?php echo $userId ?>">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <button type="submit" class="mg-btn btn btn-primary btn-block">
                                            Cập nhật
                                        </button>
                                    </div>
                                    <div class="col-sm-6"><a href="?module=users&action=list"
                                            class="mg-btn btn btn-success btn-block">Quay lại</a></div>
                                </div>
                            </form>
                            <hr>
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