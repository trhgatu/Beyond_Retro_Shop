<!-- Đăng nhập tài khoản -->
<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}

$data = [
    'pageTitle' => 'Đăng nhập tài khoản'
];

//Kiểm tra trạng thái đăng nhập
if (isLogin()) {
    redirect('?module=home&action=dashboard');
}

if (isPost()) {
    $filterAll = filter();
    if (!empty(trim($filterAll['email'])) && !empty(trim($filterAll['password']))) {
        $email = $filterAll['email'];
        $password = $filterAll['password'];

        $userQuery = oneRaw("SELECT password, id FROM user WHERE email = '$email'");

        if (!empty($userQuery)) {
            $passwordHash = $userQuery['password'];
            $userId = $userQuery['id'];
            if (password_verify($password, $passwordHash)) {
                //Tạo token login
                $tokenLogin = sha1(uniqid() . time());
                //Insert vào bảng tokenlogin
                $dataInsert = [
                    'user_id' => $userId,
                    'token' => $tokenLogin,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $insertStatus = insert('tokenlogin', $dataInsert);

                if ($insertStatus) {
                    //insert thành công
                    //lưu token login vào session
                    setSession('tokenlogin', $tokenLogin);
                    redirect('?module=home&action=dashboard'); //Chuyển hướng đến trang dashboard sau khi đăng nhập thành công
                } else {
                    setFlashData('msg', 'Không thể đăng nhập, vui lòng thử lại sau.');
                    setFlashData('msg_type', 'danger');
                }
            } else {
                setFlashData('msg', 'Mật khẩu không chính xác.');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Email không tồn tại.');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Vui lòng nhập email và mật khẩu.');
        setFlashData('msg_type', 'danger');
    }
    redirect('?module=authen&action=login');
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
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Mật khẩu" name="password">
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Đăng nhập
                                        </button>

                                        <hr>

                                    </form>


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
layouts('footer-login');
layouts('style', $data);
?>