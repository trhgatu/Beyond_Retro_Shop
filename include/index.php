<?php
$data = [
    'pageTitle' => 'Beyond Retro',
];
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Male_Fashion Template">
    <meta name="keywords" content="Male_Fashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        <?php echo !empty($data['pageTitle']) ? $data['pageTitle'] : 'Beyond Retro'; ?>
    </title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>/css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>/css/style.css" type="text/css">
</head>

<body>
    <?php
    session_start();
    require_once ("../user/config.php");
    require_once ("../db_function/connect.php");
    require_once ("../db_function/functions.php");
    require_once ("../db_function/database.php");
    require_once ("../db_function/session.php");
    layout('header', $data);
    layout('search',$data);
    layout('main', $data);
    layout('menu', $data);
    layout('footer',$data);
    ?>
    <!-- Js Plugins -->
    <script src="<?php echo _WEB_HOST_TEMPLATE; ?>/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE; ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE; ?>/js/jquery.nice-select.min.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE; ?>/js/jquery.nicescroll.min.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE; ?>/js/jquery.magnific-popup.min.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE; ?>/js/jquery.countdown.min.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE; ?>/js/jquery.slicknav.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE; ?>/js/mixitup.min.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE; ?>/js/owl.carousel.min.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE; ?>/js/main.js"></script>
</body>

</html>