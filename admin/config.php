<!-------Các hằng số của project------->
<?php
const _MODULE = 'home';
const _ACTION = 'dashboard';

const _CODE = true;
define('_WEB_HOST_ADMIN', 'http://' . $_SERVER['HTTP_HOST'] . '/Beyond_Retro/admin/');
define('_WEB_HOST_TEMPLATE', _WEB_HOST_ADMIN . '/templates');


//Thiết lập path
define('_WEB_PATH', __DIR__);
define('_WEB_PATH_TEMPLATE', _WEB_PATH . '/templates');

//Thông tin kết nối
const _HOST = 'localhost';
const _DB = 'beyond_retro';
const _USER = 'root';
const _PASS = 'mysql';

?>