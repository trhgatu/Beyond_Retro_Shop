<!-- Đăng ký tài khoản -->
<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
require_once '../class/authen.php';
$authen = new Authen($conn);

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
        $authen->register();

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

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">


    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>/css/style_login.css">

</head>

<body>
    <?php
    layout('header', $data);
    ?>
    <section class="ftco-section">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-10">
                    <div class="wrap d-md-flex">
                        <div class="img" style="background-image: url(images/bg-1.jpg);">
                        </div>
                        <div class="login-wrap p-4 p-md-5">
                            <div class="d-flex">
                                <div class="w-100">
                                    <h3 class="mb-4" style="text-transform: uppercase">Đăng ký tài khoản</h3>
                                </div>

                            </div>
                            <?php
                            if (!empty($msg)) {
                                getMSG($msg, $msg_type);
                            }
                            ?>
                            <form class="user" method="post">
                                <div class="form-group mb-3">
                                    <label class="label" for="name">Họ tên</label>
                                    <input type="text" class="form-control" placeholder="Họ tên" name="fullname" value="<?php
                                    echo old('fullname', $old) ?>">
                                    <?php
                                    echo form_error('fullname', '<span class= "error">', '</span>', $error);

                                    ?>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="label" for="name">Email</label>
                                    <input type="text" class="form-control" placeholder="Email" name="email" value="<?php
                                    echo old('email', $old)
                                        ?>">
                                    <?php
                                    echo form_error('email', '<span class= "error">', '</span>', $error);

                                    ?>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="label" for="name">Số điện thoại</label>
                                    <input type="text" class="form-control" placeholder="Số điện thoại"
                                        name="phone_number" value="<?php
                                        echo old('phone_number', $old)
                                            ?>">
                                    <?php
                                    echo form_error('phone_number', '<span class= "error">', '</span>', $error);

                                    ?>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="label" for="password">Mật khẩu</label>
                                    <input type="password" class="form-control" placeholder="Mật khẩu" name="password"
                                        value="<?php
                                        echo old('password', $old)
                                            ?>">
                                    <?php
                                    echo form_error('password', '<span class= "error">', '</span>', $error);

                                    ?>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="label" for="password">Nhập lại mật khẩu</label>
                                    <input type="password" class="form-control" placeholder="Nhập lại mật khẩu"
                                        name="password_confirm" value="<?php
                                        echo old('password_confirm', $old)
                                            ?>">
                                    <?php
                                    echo form_error('password_confirm', '<span class= "error">', '</span>', $error);

                                    ?>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="form-control submit" style="color: #ffffff; background-color: #000000;">Đăng
                                        ký</button>
                                </div>

                            </form>
                            <p class="text-center">Đã có tài khoản?<a href="?module=authen&action=login">Đăng nhập</a>
                            </p>
                            <style>
                                a{
                                    color: gray;
                                }
                                a:hover {
                                    color: #e53637;

                                }
                            </style>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
    layout('footer', $data);
    ?>

</body>

</html>