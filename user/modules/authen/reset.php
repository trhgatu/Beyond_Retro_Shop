<!-- Reset mật khẩu -->
<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}

$token = filter()['token'];
layouts('style');

if (!empty($token)) {
    //Truy vấn database kiểm tra token
    $tokenQuery = oneRaw("SELECT id, fullname, email FROM user WHERE forgotToken = '$token'");
    if (!empty($tokenQuery)) {
        $userId = $tokenQuery['id'];
        if (isPost()) {
            $filterAll = filter();
            //Mảng $error = [] chứa các lỗi
            $error = [];
            //Validate password: bắt buộc phải nhập, >= 8 ký tự
            if (empty($filterAll['password'])) {
                $error['password']['required'] = 'Mật khẩu bắt buộc phải nhập.';
            } else {
                if (strlen($filterAll['password']) < 8) {
                    $error['password']['min'] = 'Mật khẩu phải nhiều hơn 8 ký tự';
                }
            }
            //Validate password confirm: bắt buộc phải nhập, giống password
            if (empty($filterAll['password_confirm'])) {
                $error['password_confirm']['required'] = 'Bạn phải nhập lại mật khẩu.';
            } else {
                if (($filterAll['password']) != ($filterAll['password_confirm'])) {
                    $error['password_confirm']['match'] = 'Mật khẩu nhập lại không đúng.';
                }
            }
            if (empty($error)) {
                //Xử lý update mật khẩu
                $passwordHash = password_hash($filterAll['password'], PASSWORD_DEFAULT);
                $dataUpdate = [
                    'password' => $passwordHash,
                    'forgotToken' => null,
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $updateStatus = update('user', $dataUpdate,"id = '$userId'");
                if($updateStatus){
                    setFlashData('msg', 'Thay đổi mật khẩu thành công.');
                    setFlashData('msg_type', 'success');
                    redirect('?module=authen&action=login');
                }else{
                    setFlashData('msg', 'Lỗi hệ thống, vui lòng thử lại sau.');
                    setFlashData('msg_type', 'danger');
                }
            } else {
                setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu');
                setFlashData('msg_type', 'danger');
                setFlashData('error', $error);
                redirect('?module=authen&action=reset&token=' . $token);
            }
        }
        $msg = getFlashData('msg');
        $msg_type = getFlashData('msg_type');
        $error = getFlashData('error');
        ?>

        <body class="bg-gradient-primary">
            <div class="container">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                            <div class="col-lg-7">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Đặt lại mật khẩu</h1>
                                    </div>
                                    <?php
                                    if (!empty($msg)) {
                                        getMSG($msg, $msg_type);
                                    }

                                    ?>
                                    <form class="user" method="post">
                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <input type="text" class="form-control form-control-user"
                                                    id="exampleInputPassword" placeholder="Mật khẩu" name="password">
                                                <?php
                                                echo form_error('password', '<span class= "error">', '</span>', $error);

                                                ?>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-control-user"
                                                    id="exampleRepeatPassword" placeholder="Nhập lại mật khẩu"
                                                    name="password_confirm">
                                                <?php
                                                echo form_error('password_confirm', '<span class= "error">', '</span>', $error);

                                                ?>
                                            </div>
                                        </div>
                                        <input type="hidden" name="token" value="<?php echo $token ?>">
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Đặt
                                        </button>

                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="?module=authen&action=forgot">Quên mật khẩu?</a>
                                    </div>
                                    <div class=" text-center">
                                        <a class="small" href="?module=authen&action=login">Đã có tài khoản? Hãy đăng nhập!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        <?php
    } else {
        getMSG('Liên kết không tồn tại hoặc đã hết hạn.', 'danger');
    }
} else {
    getMSG('Liên kết không tồn tại hoặc đã hết hạn.', 'danger');
}


?>