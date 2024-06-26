<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
require_once '../class/order.php';
$data = [
    'pageTitle' => 'Danh sách đơn hàng'
];
$order = new Order($conn);
$listOrders = $order->listOrder();

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$error = getFlashData('error');
$old = getFlashData('old');
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
                <div class="card mb-4"
                    style="max-width: 1240px;box-shadow: rgba(9, 30, 66, 0.25) 0px 4px 8px -2px, rgba(9, 30, 66, 0.08) 0px 0px 0px 1px;"">
                    <div class=" card-header py-3" style="background-color:#CC181E">
                    <h5 class="m-0 font-weight-bold text-white">
                        Danh sách đơn hàng
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
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>SĐT</th>
                                <th>Địa chỉ </th>
                                <th>Note</th>
                                <th>Ngày đặt hàng</th>
                                <th>Trạng thái</th>
                                <th width="10%">Tổng tiền</th>
                                <th>CTĐH</th>
                                <th>Xóa</th>

                            </thead>
                            <tbody>
                                <?php
                                if (!empty($listOrders)):
                                    $count = 0; //STT
                                    foreach ($listOrders as $item):
                                        $count++;
                                        ?>
                                        <tr>
                                            <td>
                                                <p><?php echo $count; ?></p>
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
                                                <p><?php echo $item['address'] . ", " . $item['city'] . ", " . $item['country'] ?>
                                                </p>
                                            </td>
                                            <td>
                                                <p><?php echo $item['note'] ?></p>
                                            </td>
                                            <td>
                                                <p><?php echo $item['order_date'] ?></p>
                                            </td>
                                            <td>
                                                <?php
                                                if ($item['status'] == 0) {
                                                    echo '<button class="btn btn-danger btn-sm">Chưa xác nhận</button>';
                                                } else if($item['status'] == 1){
                                                    echo '<button class="btn btn-success btn-sm">Đã xác nhận</button>';
                                                }else{
                                                    echo '<button class="btn btn-danger btn-sm ">Đã hủy </button>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <p><?php echo number_format($item['total_money'], 0, ',', '.') ?>₫</p>
                                            </td>

                                            <td><a href="<?php echo _WEB_HOST_ADMIN; ?>?module=orders&action=orderdetail&id=<?php echo $item['id'] ?>"
                                                    class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i>Xem
                                                    chi tiết</a>
                                            <td><a href="<?php echo _WEB_HOST_ADMIN; ?>?module=orders&action=delete&id=<?php echo $item['id'] ?>"
                                                    onclick="return confirm('Bạn có muốn xóa đơn hàng?')"
                                                    class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a></td>
                                        </tr>
                                        <?php
                                    endforeach;
                                else:
                                    ?>
                                    <tr>
                                        <td colspan="9">
                                            <div class="alert alert-danger text-center">Không có đơn hàng nào.</div>
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

    th {
        font-size: 14px;
    }
</style>