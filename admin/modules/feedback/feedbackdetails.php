<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
require_once '../class/order.php';
$data = [
    'pageTitle' => 'Chi tiết thư'
];
$filterAll = filter();
if (!empty($filterAll['id'])) {
    $feedbackId = $filterAll['id'];
    $feedbackDetail = oneRaw("SELECT * FROM feedback WHERE id='$feedbackId'");
    if (!empty($feedbackDetail)) {
        setFlashData('order-detail', $feedbackDetail);
    } else {
        redirect('?module=feedback&action=list');
    }
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
                                <h1 class="h4 text-gray-900 mb-4">Nội dung thư </h1>
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
                                            <p>Nội dung góp ý:</p>
                                            <textarea class="form-control form-control-user" disabled
                                                style="resize: vertical; height: 100px;" name="note"><?php
                                                echo old('note', $old) ?></textarea>
                                            <?php
                                            echo form_error('phone_number', '<span class= "error">', '</span>', $error);
                                            ?>
                                        </div>



                                    </div>


                                </div>
                                <hr>
                                <div class="form-group row" style="padding-top: 30px">

                                    <div class="col-sm-6"><a href="?module=feedback&action=list"
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