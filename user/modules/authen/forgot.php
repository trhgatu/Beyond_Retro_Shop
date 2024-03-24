<!-- Quên mật khẩu -->
<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
$data = [
    'pageTitle' => 'Quên mật khẩu'
];
if (isLogin()) {
    redirect('?module=authen&action=login');
}
if (isPost()) {
    $filterAll = filter();
    if (!empty($filterAll['email'])) {
        $email = $filterAll['email'];
        $queryUser = oneRaw("SELECT id FROM user WHERE email = '$email'");
        if (!empty($queryUser)) {
            $userId = $queryUser['id'];
            //Tạo forgot token
            $forgotToken = sha1(uniqid() . time());
            $dataUpdate = [
                'forgotToken' => $forgotToken,
            ];
            $updateStatus = update('user', $dataUpdate, "id=$userId");
            if ($updateStatus) {
                //Tạo link reset, khôi phục mật khẩu
                $linkReset = _WEB_HOST . '?module=authen&action=reset&token=' . $forgotToken;
                //Gửi mail cho người dùng
                $subject = 'Yêu cầu khôi phục mật khẩu';
                $content = 'Chào bạn.</br>';
                $content .= 'Chúng tôi nhận được yêu cầu khôi phục mật khẩu từ bạn. Vui lòng truy cập vào đường link để khôi phục.';
                $content .= $linkReset . '</br>';
                $sendEmail = sendMail($email, $subject, $content);
                if ($sendEmail) {
                    setFlashData('msg', 'Vui lòng kiểm tra Email để đặt lại mật khẩu.');
                    setFlashData('msg_type', 'success');
                }
                else{
                    setFlashData('msg', 'Lỗi hệ thống, vui lòng thử lại.');
                    setFlashData('msg_type', 'danger');
                }
            } else {
                setFlashData('msg', 'Lỗi hệ thống, vui lòng thử lại.');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Địa chỉ email không tồn tại.');
            setFlashData('msg_type', 'danger');
        }

    } else {
        setFlashData('msg', 'Vui lòng nhập địa chỉ email.');
        setFlashData('msg_type', 'danger');
    }
    // redirect('?module=authen&action=forgot');
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>

<body class="bg-gradient-primary">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <?php
                                    if (!empty($msg)) {
                                        getMSG($msg, $msg_type);
                                    }
                                    ?>
                                    <form class="user" method="post">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Nhập email" name="email">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Gửi
                                        </button>
                                        <hr>
                                    </form>
                                    <div class="text-center">
                                        <a class="small" href="?module=authen&action=forgot">Đăng nhập</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="?module=authen&action=login">Chưa có tài khoản? Đăng
                                            ký!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php
layouts('style', $data);
?>