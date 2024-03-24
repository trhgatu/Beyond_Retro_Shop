<!-- Kích hoạt tài khoản -->
<?php
if (!defined("_CODE")) {
    die ("Access Denied !");
}
require_once '../class/authen.php';

$authen = new Authen($conn);

if (!empty ($token)) {
    //Truy vấn kiểm tra token với db
    $activeStatus = $authen->active();
    if ($activeStatus) {
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

