<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
$data = [
    'pageTitle' => 'Danh sách người dùng'
];
require_once '../class/user.php';
require_once '../class/admin.php';

if (!isAdminLogin()) {
    redirect('?module=authen&action=login');
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
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
                <div class="card mb-4" style="max-width: 1240px; box-shadow: rgba(9, 30, 66, 0.25) 0px 4px 8px -2px, rgba(9, 30, 66, 0.08) 0px 0px 0px 1px;">
                    <div class="card-header py-3" style="background-color:#CC181E">
                        <h5 class="m-0 font-weight-bold text-white">
                            Danh sách người dùng
                            <a href="?module=users&action=add" class="btn btn-light btn-sm" style="float:right">Thêm
                                người dùng <i class="fa-solid fa-plus"></i></a>
                        </h5>
                    </div>
                    <?php
                    if (!empty($msg)) {
                        getMSG($msg, $msg_type);
                    }
                    ?>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <th>STT</th>
                                    <th width="80px">Loại</th>
                                    <th>Avatar</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>SĐT</th>
                                    <th>Hoạt động</th>
                                    <th>Trạng thái</th>
                                    <th>Lần đăng nhập cuối</th>
                                    <th width="5%">Sửa</th>
                                    <th width="5%">Xóa</th>

                                </thead>
                                <tbody>
                                    <?php
                                    $user = new User($conn);
                                    $admin = new Admin($conn);
                                    $listUsers = $user->list();
                                    if (!empty($listUsers)):
                                        $count = 0;
                                        foreach ($listUsers as $item):
                                            $count++;
                                            ?>
                                            <tr>
                                                <td>
                                                   <p><?php echo $count; ?></p>
                                                </td>
                                                <td>
                                                    <p>
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
                                                    </p>

                                                </td>
                                                <td>
                                                    <img src="../images/avatar/<?php echo $item['avatar'] ?>"
                                                        style="max-width: 50px;">
                                                </td>
                                                <td>
                                                    <p><?php echo $item['fullname'] ?></p>
                                                </td>
                                                <td>
                                                    <p><?php echo $item['email'] ?></p>
                                                </td>
                                                <td>
                                                    <p><?php echo $item['phone_number'] ?></p>
                                                </td>
                                                <td>
                                                    <p><?php
                                                    if ($item['online']) {
                                                        echo '<button class="btn btn-success btn-sm">Online</button>';
                                                    } else {
                                                        echo '<button class="btn btn-danger btn-sm">Offline</button>';
                                                    }
                                                    ?></p>

                                                </td>
                                                <td>
                                                    <?php
                                                    if ($item['status'] == 1) {
                                                        echo '<button class="btn btn-success btn-sm">Đã KH</button>';
                                                    } else {
                                                        echo '<button class="btn btn-danger btn-sm">Chưa kích hoạt</button>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <p><?php echo date("d-m-Y H:i:s", strtotime($item['last_active'])); ?></p>
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
<style>
    p {
        font-size: 13px;
    }
    th{
        font-size: 14px;
    }
</style>