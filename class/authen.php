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

    public function login_user()
    {
        $filterAll = filter();
        if (!empty(trim($filterAll['email'])) && !empty(trim($filterAll['password']))) {
            $email = $filterAll['email'];
            $password = $filterAll['password'];

            $userQuery = oneRaw("SELECT id, password, activeToken, status FROM user WHERE email = '$email'");

            if (!empty($userQuery)) {
                $passwordHash = $userQuery['password'];
                $userId = $userQuery['id'];
                $activeToken = $userQuery['activeToken'];
                $status = $userQuery['status'];

                if (!empty($activeToken && $status == 0 || empty($activeToken) && $status == 0)) {
                    setFlashData('msg', 'Tài khoản của bạn chưa được kích hoạt, vui lòng kiểm tra Email để kích hoạt tài khoản.');
                    setFlashData('msg_type', 'danger');
                    redirect('../user/?module=authen&action=login');
                    exit();
                } else {
                    if (password_verify($password, $passwordHash)) {
                        $tokenLogin = sha1(uniqid() . time());
                        $dataInsert = [
                            'user_id' => $userId,
                            'token' => $tokenLogin,
                            'created_at' => date('Y-m-d H:i:s'),
                        ];
                        $insertStatus = insert('tokenlogin', $dataInsert);

                        if ($insertStatus) {
                            setSession('user_id', $userId);
                            setSession('tokenlogin', $tokenLogin);
                            redirect('http://localhost/Beyond_Retro/include/');
                        } else {
                            setFlashData('msg', 'Không thể đăng nhập, vui lòng thử lại sau.');
                            setFlashData('msg_type', 'danger');
                        }
                    } else {
                        setFlashData('msg', 'Mật khẩu không chính xác.');
                        setFlashData('msg_type', 'danger');
                    }
                }
            } else {
                setFlashData('msg', 'Email không tồn tại.');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Vui lòng nhập email và mật khẩu.');
            setFlashData('msg_type', 'danger');
        }
    }


    public static function login_admin()
    {
        $filterAll = filter();
        if (!empty(trim($filterAll['email'])) && !empty(trim($filterAll['password']))) {
            $email = $filterAll['email'];
            $password = $filterAll['password'];

            $userQuery = oneRaw("SELECT id, password, role_id FROM user WHERE email = '$email'");

            if (!empty($userQuery)) {
                $passwordHash = $userQuery['password'];
                $adminId = $userQuery['id'];
                $roleId = $userQuery['role_id'];

                $roleQuery = oneRaw("SELECT name FROM role WHERE id = $roleId");
                $userRole = $roleQuery['name'];

                if (password_verify($password, $passwordHash)) {
                    if ($userRole == 'Admin') {
                        $tokenLogin = sha1(uniqid() . time());
                        $dataInsert = [
                            'user_id' => $adminId,
                            'token' => $tokenLogin,
                            'created_at' => date('Y-m-d H:i:s'),
                        ];
                        $insertStatus = insert('tokenlogin_admin', $dataInsert);
                        if ($insertStatus) {
                            setSession('admin_id', $adminId);
                            setSession('tokenlogin_admin', $tokenLogin);
                            redirect('?module=home&action=dashboard');
                        } else {
                            setFlashData('msg', 'Không thể đăng nhập, vui lòng thử lại sau.');
                            setFlashData('msg_type', 'danger');
                            redirect('?module=authen&action=login');
                        }
                    } else {
                        setFlashData('msg', 'Bạn không có quyền truy cập.');
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
    }

    public static function logout_user($conn, $user_id)
    {
        if (getSession('tokenlogin') && getSession('user_id')) {
            $token = getSession('tokenlogin');
            $userId = getSession('user_id');


            delete('tokenlogin', "token = '$token'");

            $updateUserQuery = "UPDATE user SET last_active = NOW() WHERE id = :user_id";
            $stmtUser = $conn->prepare($updateUserQuery);
            $stmtUser->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmtUser->execute();

            // Xóa session
            removeSession('tokenlogin');
            removeSession('user_id');
            removeSession('cart');
            removeSession('cart_count');

            // Chuyển hướng người dùng
            redirect('http://localhost/Beyond_Retro/include/');
        }

    }


    public static function logout_admin($conn, $user_id)
    {
        if (getSession('tokenlogin_admin') && getSession('admin_id')) {
            $token = getSession('tokenlogin_admin');
            $adminId = getSession('admin_id');


            delete('tokenlogin_admin', "token = '$token'");

            $updateUserQuery = "UPDATE user SET last_active = NOW() WHERE id = :user_id";
            $stmtUser = $conn->prepare($updateUserQuery);
            $stmtUser->bindParam(':user_id', $adminId, PDO::PARAM_INT);
            $stmtUser->execute();

            // Xóa session
            removeSession('tokenlogin_admin');
            removeSession('admin_id');

            redirect('?module=authen&action=login');
        }
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
            $linkActive = _WEB_HOST . '?module=authen&action=active&token=' . $activeToken;
            $subject = $filterAll['fullname'] .', '.  'Hãy kích hoạt tài khoản Beyond Retro của bạn';
            $content = 'Chào ' . $filterAll['fullname'] . ',' .' Chào mừng bạn đến với Beyond Retro !' . '<br>';
            $content .= 'Cảm ơn bạn đã đăng ký tài khoản tại Beyond Retro. Để hoàn tất quá trình đăng ký và bắt đầu mua sắm, bạn cần kích hoạt tài khoản của mình.  <br>';
            $content .= 'Vui lòng nhấp vào liên kết dưới đây để kích hoạt tài khoản của bạn: ' . '<br>';
            $content .= $linkActive . '<br>';
            $content .= 'Nếu bạn gặp bất kỳ vấn đề nào trong quá trình kích hoạt tài khoản, vui lòng liên hệ với chúng tôi qua email support@beyondretro.com.' . '<br>';
            $content .= 'Chúng tôi rất mong được phục vụ bạn!' . '<br>';
            $content .= 'Trân trọng' . '<br>';
            $content .= 'Đội ngũ hỗ trợ Beyond Retro' . '<br>';

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
        redirect('?module=authen&action=login');
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



