<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
require_once '../class/order.php';
$data = [
    'pageTitle' => 'Danh sách đơn hàng'
];

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$error = getFlashData('error');
$old = getFlashData('old');

?>

<?php layout('header', $data) ?>
<section class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__text">
                    <h4>Đơn mua</h4>
                    <div class="breadcrumb__links">
                        <span>Tất cả đơn mua</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
// Lấy danh sách đơn hàng
$orderDetails = getRaw("SELECT * FROM orders");
if ($orderDetails) {
    foreach ($orderDetails as $order) {
        ?>
        <section class="shopping-cart spad">
            <div class="container" style="box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, rgba(255, 255, 255, 0.08) 0px 1px 0px inset;">
                <div class="row">
                    <div class="col-lg-8">
                        <h1>mã đơn hàng: <td><?php echo $order['id']?></td></h1>
                        <div class="shopping__cart__table">
                            <table>
                                <thead>
                                    <tr>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php

                                        $orderId = $order['id'];
                                        $orderProducts = getRaw("SELECT * FROM order_details WHERE order_id='$orderId'");
                                        if ($orderProducts) {
                                            foreach ($orderProducts as $product) {
                                                $productId = $product['product_id'];
                                                $productDetail = oneRaw("SELECT * FROM product WHERE id='$productId'");
                                                ?>
                                            <tr>

                                                <td class="product__cart__item">
                                                    <div class="product__cart__item__pic">
                                                        <img src="../images/products/thumbnail/<?php echo $productDetail['thumbnail']; ?>" alt=""
                                                            style="max-width: 70px">
                                                    </div>
                                                    <div class="product__cart__item__text">
                                                        <h6><?php echo $productDetail['name']; ?></h6>
                                                        <h5><?php echo number_format($productDetail['price'], 0, ',', '.') ?>₫
                                                        </h5>
                                                    </div>
                                                </td>
                                                <td class="quantity__item">
                                                    <div class="quantity">
                                                        <div class="pro-qty-2">

                                                            <?php echo $product['num'] ?></b>

                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="cart__price">
                                                    <?php echo number_format($product['price'], 0, ',', '.') ?>₫
                                                </td>
                                            </tr>
                                            <?php
                                            }
                                            ?>
                                        <?php
                                        }

                                        ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="col-lg-4">
                        <div class="cart__total">
                            <h6>Thành tiền</h6>
                            <ul>
                                <li>Tổng
                                    tiền: <span><?php echo number_format($order['total_money'], 0, ',', '.') ?>₫</span>
                                </li>
                                <li>
                                    <div class="form-group">
                                        <label for="status">Trạng thái đơn hàng:</label>
                                        <?php
                                        if ($order['status'] == 0) {
                                            echo "Đang chờ để xác nhận";

                                        } else if ($order['status'] == 1) {
                                            echo "Đã được xác nhận.";
                                        }
                                        ?>
                                    </div>

                                </li>
                            </ul>

                        </div>


                    </div>
                </div>
            </div>

        </section>
        <?php
    }
?>
<?php
} else {
    echo 'Bạn chưa đặt đơn hàng nào.';
}
?>




<?php
// Include footer
layout('footer', $data);
?>
</div>