<!-- Đăng nhập tài khoản -->
<?php
if (!defined("_CODE")) {
	die("Access Denied !");
}
require_once '../class/authen.php';
$authen = new Authen($conn);
$data = [
	'pageTitle' => 'Đăng nhập tài khoản'
];

//Kiểm tra trạng thái đăng nhập

if (isPost()) {
	$authen->login();
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>

<!doctype html>
<html lang="en">

<head>
	<title>Đăng nhập</title>
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
									<h3 class="mb-4" style="text-transform: uppercase">Đăng nhập</h3>
								</div>

							</div>
							<?php if (!empty($msg)) {
								getMSG($msg, $msg_type);
							} ?>
							<form class="user" method="post">
								<div class="form-group mb-3">
									<label class="label" for="name">Email</label>
									<input type="text" class="form-control" placeholder="Email" name="email">
								</div>
								<div class="form-group mb-3">
									<label class="label" for="password">Password</label>
									<input type="password" class="form-control" placeholder="Password" name="password">
								</div>
								<div class="form-group">
									<button type="submit" class="form-control" style="color: #ffffff; background-color: #000000;">Đăng
										nhập</button>
								</div>
								<div class="form-group d-md-flex">
									<div class="w-50 text-md-right" style="padding-right: 73px">
										<a href="?module=authen&action=forgot">Quên mật khẩu</a>
									</div>
									<div class="w-50 text-md-left" style="padding-left: 49px;">
										<p class="text-center">Chưa có tài khoản? <a
												href="?module=authen&action=register">Đăng ký</a>
										</p>
									</div>

								</div>
								<style>
									.form-group a:hover {
										color: #e53637;

									}
								</style>
							</form>

							</>
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