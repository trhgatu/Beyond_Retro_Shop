<?php
if (!defined("_CODE")) {
    die ("Access Denied !");
}
$data = [
    'pageTitle' => 'Danh sách người dùng'
];
//Kiểm tra trạng thái đăng nhập
if (!isAdminLogin()) {
    redirect('?module=authen&action=login');
}
require_once '../class/user.php';
//Truy vấn vào bảng user
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
//$error = getFlashData('error');
//$old = getFlashData('old');
?>
<div id="wrapper">
    <?php
    layout_admin('style', $data);
    layout_admin('sidebar', $data);
    ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php
            layout_admin('header', $data);
            ?>
            <div class="container-fluid">
                <div class="card shadow mb-4" style="max-width: 1240px">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Danh sách người dùng
                            <a href="?module=users&action=add" class="btn btn-success btn-sm" style="float:right">Thêm
                                người dùng<i class="fa-solid fa-plus"></i></a>
                        </h6>
                    </div>
                    <?php
                    if (!empty ($msg)) {
                        getMSG($msg, $msg_type);
                    }
                    ?>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <th>STT</th>
                                    <th width="80px">Loại</th>
                                    <th>Ảnh đại diện</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Trạng thái</th>
                                    <th width="5%">Sửa</th>
                                    <th width="5%">Xóa</th>

                                </thead>
                                <tbody>
                                    <?php
                                    $user = new User($conn);
                                    $listUsers = $user->listUser();

                                    if (!empty ($listUsers)):
                                        $count = 0; //STT
                                        foreach ($listUsers as $item):
                                            $count++;
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $count; ?>
                                                </td>
                                                <td>
                                                <?php
                                                    $role_id = $item['role_id'];
                                                    $role_name = "";

                                                    $stmt = $conn->prepare("SELECT name FROM role WHERE id = :role_id");
                                                    $stmt->bindParam(':role_id', $role_id);
                                                    $stmt->execute();
                                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                    if ($result) {
                                                        $role_name = $result['name'];
                                                    }
                                                    echo $role_name;
                                                    ?>
                                                </td>
                                                <td>
                                                    <img src="../images/avatar/<?php echo $item['avatar'] ?>"
                                                        style="max-width: 170px;">
                                                </td>
                                                <td>
                                                    <?php echo $item['fullname'] ?>
                                                </td>
                                                <td>
                                                    <?php echo $item['email'] ?>
                                                </td>
                                                <td>
                                                    <?php echo $item['phone_number'] ?>
                                                </td>
                                                <td>
                                                    <?php echo $item['status'] == 1 ? '<button class="btn btn-success btn-sm">Đã kích hoạt</button>' : '<button class="btn btn-danger btn-sm">Chưa kích hoạt</button>'; ?>
                                                </td>
                                                <td><a href="<?php echo _WEB_HOST_ADMIN; ?>?module=users&action=edit&id=<?php echo $item['id'] ?>"
                                                        class="btn btn-warning btn-sm"><i
                                                            class="fa-solid fa-pen-to-square"></i></a>
                                                </td>
                                                <td><a href="<?php echo _WEB_HOST_ADMIN; ?>?module=users&action=delete&id=<?php echo $item['id'] ?>"
                                                        onclick="return confirm('Bạn có muốn xóa?')"
                                                        class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a></td>
                                            </tr>
                                            <?php
                                        endforeach;
                                    else:
                                        ?>
                                        <tr>
                                            <td colspan="7">
                                                <div class="alert alert-danger text-center">Không có người dùng nào</div>


                                        </tr>
                                        <?php
                                    endif;

                                    ?>


                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
       layout_admin('footer', $data);
        ?>
    </div>

</div>