    <!-- Đăng ký tài khoản -->
    <?php
    if (!defined("_CODE")) {
        die("Access Denied !");
    }
    $data = [
        'pageTitle' => 'Đăng ký tài khoản'
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
            $sql = "SELECT * FROM user WHERE email = '$email'";
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
            $activeToken = sha1(uniqid() . time());
            $dataInsert = [
                'fullname' => $filterAll['fullname'],
                'email' => $filterAll['email'],
                'phone_number' => $filterAll['phone_number'],
                'password' => password_hash($filterAll['password'], PASSWORD_DEFAULT),
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
        } else {
            setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu');
            setFlashData('msg_type', 'danger');
            setFlashData('error', $error);
            setFlashData('old', $filterAll);
            redirect('?module=authen&action=register');
        }

    }

    $msg = getFlashData('msg');
    $msg_type = getFlashData('msg_type');
    $error = getFlashData('error');
    $old = getFlashData('old');
    ?>
    <body class="bg-gradient-primary">
        <div class="container">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                        <div class="col-lg-7">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Đăng ký tài khoản</h1>
                                </div>
                                <?php
                                if (!empty($msg)) {
                                    getMSG($msg, $msg_type);
                                }
                                ?>
                                <form class="user" method="post">
                                    <div class="form-group">
                                        <input type="fullname" class="form-control form-control-user" id="exampleInputEmail"
                                            placeholder="Họ tên" name="fullname" value="<?php
                                            echo old('fullname', $old)
                                                ?>">
                                        <?php
                                        echo form_error('fullname', '<span class= "error">', '</span>', $error);

                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user" id="exampleInputEmail"
                                            placeholder="Địa chỉ email" name="email" value="<?php
                                            echo old('email', $old)
                                                ?>">
                                        <?php
                                        echo form_error('email', '<span class= "error">', '</span>', $error);

                                        ?>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Số điện thoại" name="phone_number"
                                                value="<?php
                                                echo old('phone_number', $old)
                                                    ?>">
                                            <?php
                                            echo form_error('phone_number', '<span class= "error">', '</span>', $error);

                                            ?>
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Mật khẩu" name="password" value="<?php
                                                echo old('password', $old)
                                                    ?>">
                                            <?php
                                            echo form_error('password', '<span class= "error">', '</span>', $error);

                                            ?>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control form-control-user"
                                                id="exampleRepeatPassword" placeholder="Nhập lại mật khẩu"
                                                name="password_confirm" value="<?php
                                                echo old('password_confirm', $old)
                                                    ?>">
                                            <?php
                                            echo form_error('password_confirm', '<span class= "error">', '</span>', $error);

                                            ?>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Đăng ký
                                    </button>
                                    <hr>
                                    <a href="index.html" class="btn btn-google btn-user btn-block">
                                        <i class="fab fa-google fa-fw"></i> Đăng ký với Google
                                    </a>
                                    <a href="index.html" class="btn btn-facebook btn-user btn-block">
                                        <i class="fab fa-facebook-f fa-fw"></i> Đăng ký với Facebook
                                    </a>
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
    layouts('style',$data);
    ?>