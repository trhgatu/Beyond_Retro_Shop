<!-- Quên mật khẩu -->
<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
$data = [
    'pageTitle' => 'Quên mật khẩu'
];
require_once '../class/authen.php';
$authen = new Authen($conn);

if (isPost()) {
    $authen->forgot();
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="templates/css/style_login.css">

</head>

<body>
<?php
	layout('header', $data);
	?>
    <section class="ftco-section">
        <div class="container"  style="margin-top: 50px">
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-10">
                    <div class="wrap d-md-flex">
                        <div class="img" style="background-image: url(../img/forgot_background.jpg);">
                        </div>
                        <div class="login-wrap p-4 p-md-5">
                            <div class="d-flex">
                                <div class="w-100">
                                    <h3 class="mb-4" style="text-transform: uppercase">Quên mật khẩu</h3>
                                </div>

                            </div>
                            <?php
                            if (!empty($msg)) {
                                getMSG($msg, $msg_type);
                            }
                            ?>
                            <form class="user" method="post">
                                <div class="form-group mb-3">
                                    <label class="label" for="name">Email</label>
                                    <input type="text" class="form-control" placeholder="Email" name="email">
                                </div>

                                <button type="submit" class="btn btn-primary btn-user btn-block" style="background-color: #000000">
                                    Gửi
                                </button>
                                <hr>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <?php
	layout('footer', $data);
	?>
</body>

</html>