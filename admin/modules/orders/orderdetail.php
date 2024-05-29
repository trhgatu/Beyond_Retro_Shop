<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
require_once '../class/order.php';
$data = [
    'pageTitle' => 'Danh sách đơn hàng'
];
$filterAll = filter();
if (!empty($filterAll['id'])) {
    $orderId = $filterAll['id'];
    $orderDetail = oneRaw("SELECT * FROM orders WHERE id='$orderId'");
    if (!empty($orderDetail)) {
        setFlashData('order-detail', $orderDetail);
    } else {
        redirect('?module=orders&action=list');
    }
}

if (isPost() && isset($_POST['confirm_order'])) {
    $filterAll = filter();
    $dataUpdate = [
        'status' => 1,
    ];
    $condition = "id = $orderId";
    $updateStatus = update('orders', $dataUpdate, $condition);
    if ($updateStatus) {
        setFlashData('msg', 'Cập nhật trạng thái đơn hàng thành công !');
        setFlashData('msg_type', 'success');
        redirect("?module=orders&action=list");
    } else {
        setFlashData('msg', 'Cập nhật trạng thái đơn hàng thất bại !');
        setFlashData('msg_type', 'danger');
    }
    redirect('?module=orders&action=orderdetail&id=' . $orderId);
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$error = getFlashData('error');
$old = getFlashData('old');
$orderDetails = getFlashData('order-detail');
if ($orderDetails) {
    $old = $orderDetails;
}

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
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Chi tiết đơn hàng </h1>
                            </div>
                            <?php
                            if (!empty($msg)) {
                                getMSG($msg, $msg_type);
                            }
                            ?>
                            <form method="post">
                                <div class="row">
                                    <div class="col"
                                        style="border: 2px solid;padding: 30px;border-radius: 20px;background-color: #e1e1e1">
                                        <div class="form-group">
                                            <p>Họ tên khách hàng</p>
                                            <input type="text" class="form-control form-control-user" name="fullname"
                                                value="<?php echo old('fullname', $old) ?>" disabled>
                                            <?php
                                            echo form_error('name', '<span class= "error">', '</span>', $error);
                                            ?>
                                        </div>
                                        <div class="form-group">
                                            <p>Email:</p>
                                            <input type="text" class="form-control form-control-user" name="email"
                                                value="<?php echo old('email', $old) ?>" disabled>
                                            <?php
                                            echo form_error('email', '<span class= "error">', '</span>', $error);
                                            ?>
                                        </div>
                                        <div class="form-group">
                                            <p>Số điện thoại:</p>
                                            <input type="text" class="form-control form-control-user"
                                                name="phone_number" value="<?php
                                                echo old('phone_number', $old)
                                                    ?>" disabled>
                                            <?php
                                            echo form_error('phone_number', '<span class= "error">', '</span>', $error);
                                            ?>
                                        </div>
                                        <div class="form-group">
                                            <p>Địa chỉ:</p>
                                            <input type="text" class="form-control form-control-user" name="address"
                                                value="<?php
                                                echo old('address', $old)
                                                    ?>" disabled>
                                            <?php
                                            echo form_error('phone_number', '<span class= "error">', '</span>', $error);
                                            ?>
                                        </div>

                                        <div class="form-group">
                                            <p>Ghi chú cho đơn hàng:</p>
                                            <textarea class="form-control form-control-user" disabled
                                                style="resize: vertical; height: 100px;" name="note"><?php
                                                echo old('note', $old) ?></textarea>
                                            <?php
                                            echo form_error('phone_number', '<span class= "error">', '</span>', $error);
                                            ?>
                                        </div>



                                    </div>

                                    <div class="col"
                                        style="border: 2px solid;padding: 30px;border-radius: 20px;background-color: #e1e1e1; margin-left: 20px">
                                        <h4>Các sản phẩm đã đặt:</h4>
                                        <div class="form-group">
                                            <?php
                                            $order_id = $orderDetails['id'];
                                            $orderProducts = getRaw("SELECT * FROM order_details WHERE order_id='$order_id'");
                                            foreach ($orderProducts as $product):
                                                $product_id = $product['product_id'];
                                                $productDetail = oneRaw("SELECT * FROM product WHERE id='$product_id'");
                                                ?>
                                                <div class="form-group">
                                                    <img src="../images/products/thumbnail/<?php echo $productDetail['thumbnail']; ?>"
                                                        alt=""
                                                        style="max-width: 70px; border: 2px solid #e1e1e1; border-radius: 15px">
                                                    <b><?php echo $productDetail['name'] ?> / Số lượng:
                                                        <?php echo $product['num'] ?></b>
                                                    <p>Giá của sản phẩm:
                                                        <?php echo number_format($product['price'], 0, ',', '.') ?>₫
                                                    </p>
                                                </div>
                                                <?php
                                            endforeach;
                                            ?>
                                        </div>
                                        <div class="form-group">
                                            <p>Tổng tiền:</p>
                                            <input type="text" class="form-control form-control-user" name="totalPrice"
                                                disabled value="<?php
                                                echo isset($orderDetails['total_money']) ? number_format($orderDetails['total_money'], 0, ',', '.') : '0';
                                                ?>₫">
                                            <?php
                                            echo form_error('totalPrice', '<span class= "error">', '</span>', $error);
                                            ?>
                                        </div>


                                        <div class="form-group">
                                            <p>Ngày đặt hàng</p>
                                            <input type="text" class="form-control form-control-user" name="order_date"
                                                value="<?php
                                                echo old('order_date', $old)
                                                    ?>" disabled>
                                            <?php
                                            echo form_error('order_date', '<span class= "error">', '</span>', $error);
                                            ?>
                                        </div>
                                        <?php $status = isset($orderDetail['status']) ? $orderDetail['status'] : 0; ?>
                                        <div class="form-group">
                                            <label for="status">Trạng thái đơn hàng:</label>
                                            <?php
                                            if ($status == 0) {
                                                echo "Đơn hàng đang chờ để xác nhận.";
                                            } else if ($status == 1) {
                                                echo "Đơn hàng đã được xác nhận.";
                                            }else{
                                                echo "Đơn hàng đã hủy.";
                                            }
                                            ?>
                                        </div>


                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row" style="padding-top: 30px">
                                    <input type="hidden" name="id" value="<?php echo $orderDetails['id']; ?>">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <?php
                                        if ($status == 0) {
                                            ?>
                                            <button type="submit" class="mg-btn btn btn-primary btn-block"
                                                name="confirm_order">
                                                Xác nhận đơn hàng
                                            </button>
                                            <?php
                                        }
                                        ?>

                                    </div>
                                    <div class="col-sm-6"><a href="?module=orders&action=list"
                                            class="mg-btn btn btn-success btn-block">Quay lại</a></div>
                                </div>
                            </form>

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