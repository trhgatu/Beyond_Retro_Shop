<?php
class Authen
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    function active()
    {
        $token = filter()['token'];
        if (!empty($token)) {
            $tokenQuery = oneRaw("SELECT id FROM user WHERE activeToken = '$token'");
            if (!empty($tokenQuery)) {
                $userId = $tokenQuery['id'];
                $dataUpdate = [
                    'status' => 1,
                    'activeToken' => null
                ];

                $updateStatus = update('user', $dataUpdate, "id = $userId");

                if ($updateStatus) {
                    setFlashData('msg', 'Kích hoạt tài khoản thành công.');
                    setFlashData('msg_type', 'success');
                } else {
                    setFlashData('msg', 'Kích hoạt tài khoản không thành công.');
                    setFlashData('msg_type', 'danger');
                }
                redirect('?module=authen&action=login');
            } else {
                getMSG('Liên kết không tồn tại hoặc đã hết hạn.', 'danger');
            }

        } else {
            getMSG('Liên kết không tồn tại hoặc đã hết hạn.', 'danger');
        }
    }
    public static function login()
    {
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
                        redirect('/Beyond_Retro/include/');
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
    public static function logout()
    {
        $token = getSession('tokenlogin');
        delete('tokenlogin', "token='$token'");
        removeSession('tokenlogin');
        redirect('http://localhost/Beyond_Retro/include/');
    }
    public static function register()
    {
        $filterAll = filter();
        $activeToken = sha1(uniqid() . time());
        $roleQuery = oneRaw("SELECT id FROM role WHERE name = 'User'");
        $roleId = $roleQuery['id'];
        $dataInsert = [
            'fullname' => $filterAll['fullname'],
            'avatar' => 'default.jpg',
            'email' => $filterAll['email'],
            'phone_number' => $filterAll['phone_number'],
            'password' => password_hash($filterAll['password'], PASSWORD_DEFAULT),
            'role_id' => $roleId,
            'activeToken' => $activeToken,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $insertStatus = insert('user', $dataInsert);


        if ($insertStatus) {
            //Tạo link kích hoạt tài khoản
            $linkActive = _WEB_HOST . '?module=authen&action=active&token=' . $activeToken;
            //Thiết lập gửi mail
            $subject = $filterAll['fullname'] . 'Vui lòng kích hoạt tài khoản';
            $content = 'Chào' . $filterAll['fullname'] . '.</>';
            $content .= 'Click vào link này để kích hoạt tài khoản : </br>';
            $content .= $linkActive . '</br>';
            $content .= 'Trân trọng cảm ơn';
            //GỬi mail
            $sendMail = sendMail($filterAll['email'], $subject, $content);
            if ($sendMail) {
                setFlashData('msg', 'Đăng ký thành công, vui lòng kiểm tra email để kích hoạt tài khoản');
                setFlashData('msg_type', 'success');
            } else {
                setFlashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
                setFlashData('msg_type', 'danger');
            }

        } else {
            setFlashData('msg', 'Đăng ký thất bại');
            setFlashData('msg_type', 'danger');
        }
        redirect('?module=authen&action=register');
    }
    public static function forgot()
    {
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
                    } else {
                        setFlashData('msg', 'Lỗi hệ thống, vui lòng thử lại sau.');
                        setFlashData('msg_type', 'danger');
                    }
                } else {
                    setFlashData('msg', 'Lỗi hệ thống, vui lòng thử lại sau.');
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
    public static function reset()
    {
        $token = filter()['token'];
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
                        $passwordHash = password_hash($filterAll['password'], PASSWORD_DEFAULT);
                        $dataUpdate = [
                            'password' => $passwordHash,
                            'forgotToken' => null,
                            'updated_at' => date('Y-m-d H:i:s')
                        ];
                        $updateStatus = update('user', $dataUpdate, "id = '$userId'");
                        if ($updateStatus) {
                            setFlashData('msg', 'Thay đổi mật khẩu thành công.');
                            setFlashData('msg_type', 'success');
                            redirect('?module=authen&action=login');
                        } else {
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

                <head>
                    <title>Login 04</title>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

                    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

                    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

                    <link rel="stylesheet" href="templates/css/style_login.css">

                </head>

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
    }

}



