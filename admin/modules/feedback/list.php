<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
require_once '../class/feedback.php';
$data = [
    'pageTitle' => 'Hộp thư góp ý'
];
$feedback = new Feedback($conn);
$listFeedback = $feedback->list();

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
                        Hộp thư góp ý
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
                                <th>Ngày gửi</th>
                                <th>Chi tiết</th>
                                <th>Xóa</th>

                            </thead>
                            <tbody>
                                <?php
                                if (!empty($listFeedback)):
                                    $count = 0; //STT
                                    foreach ($listFeedback as $item):
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
                                                <p><?php echo $item['sent_at'] ?></p>
                                            </td>
                                            <td><a href="<?php echo _WEB_HOST_ADMIN; ?>?module=feedback&action=feedbackdetails&id=<?php echo $item['id'] ?>"
                                                    class="btn btn-info btn-sm"><i class="fa-solid fa-pen-to-square"></i> Xem
                                                    thư </a>
                                                    <td><a href="<?php echo _WEB_HOST_ADMIN; ?>?module=feedback&action=delete&id=<?php echo $item['id'] ?>"
                                                        onclick="return confirm('Bạn có muốn xóa thư?')"
                                                        class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a></td>
                                        </tr>
                                        <?php
                                    endforeach;
                                else:
                                    ?>
                                    <tr>
                                        <td colspan="9">
                                            <div class="alert alert-danger text-center">Không có thư nào.</div>
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