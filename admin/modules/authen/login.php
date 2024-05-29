<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
require_once '../class/authen.php';
$authen = new Authen($conn);
$data = [
    'pageTitle' => 'Đăng nhập tài khoản'
];
if (isPost()) {
    $authen->login_admin();
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>
<style>
    body{
        background-image: url("../img/background_admin.jpg");
    }
</style>
<body class="bg-gradient">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9" style="padding-top: 80px">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0" style="background-color: #000000">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image">
                                <img src="../img/admin_background.jpg" style="width: 100%; padding-top: 38px">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-white mb-4" style="text-transform: uppercase">Chào mừng trở lại!</h1>
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
                                                placeholder="Email" name="email">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Mật khẩu" name="password">
                                        </div>

                                        <button type="submit" class="btn btn-dark btn-user btn-block">
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
layout_admin('footer-login');
layout_admin('style', $data);
?>