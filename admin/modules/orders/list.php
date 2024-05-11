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
                <div class="card shadow mb-4" style="max-width: 1240px">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Danh sách đơn hàng

                        </h6>

                    </div>
                    <?php
                    if (!empty($msg)) {
                        getMSG($msg, $msg_type);
                    }
                    ?>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <th>STT</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Địa chỉ </th>
                                    <th>Note</th>
                                    <th>Ngày đặt hàng</th>
                                    <th>Trạng thái</th>
                                    <th width="10%">Tổng tiền</th>

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
                                                    <?php echo $count; ?>
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
                                                    <?php echo $item['address'] ?>
                                                </td>
                                                <td>
                                                    <?php echo $item['note'] ?>
                                                </td>
                                                <td>
                                                    <?php echo $item['order_date'] ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($item['status'] == 0) {
                                                        echo '<button class="btn btn-danger btn-sm">Chưa xác nhận</button>';
                                                    } else {
                                                        echo '<button class="btn btn-success btn-sm">Đã xác nhận</button>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php echo number_format($item['total_money'], 0, ',', '.') ?>₫
                                                </td>

                                                <td><a href="<?php echo _WEB_HOST_ADMIN; ?>?module=orders&action=orderdetail&id=<?php echo $item['id'] ?>"
                                                        class="btn btn-warning btn-sm"><i
                                                            class="fa-solid fa-pen-to-square"></i>Xem chi tiết</a>
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