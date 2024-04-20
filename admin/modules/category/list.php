<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
require_once '../class/category.php';
$data = [
    'pageTitle' => 'Danh sách danh mục'
];
//Kiểm tra trạng thái đăng nhập
if (!isAdminLogin()) {
    redirect('?module=authen&action=login');
}
$category = new Category($conn);
$listCategories = $category->listCategories();

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
                            Danh sách danh mục sản phẩm
                            <a href="?module=category&action=add" class="btn btn-success btn-sm"
                                style="float:right">Thêm danh mục mới<i class="fa-solid fa-plus"></i></a>
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
                                    <th>Tên danh mục</th>
                                    <th width="5%">Xóa</th>

                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($listCategories)):
                                        $count = 0; //STT
                                        foreach ($listCategories as $item):
                                            $count++;
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $count; ?>
                                                </td>
                                                <td>
                                                    <?php echo $item['name'] ?>
                                                </td>


                                                <td><a href="<?php echo _WEB_HOST_ADMIN; ?>?module=category&action=delete&id=<?php echo $item['id'] ?>"
                                                        onclick="return confirm('Bạn có muốn xóa?')"
                                                        class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a></td>
                                            </tr>
                                            <?php
                                        endforeach;
                                    else:
                                        ?>
                                        <tr>
                                            <td colspan="7">
                                                <div class="alert alert-danger text-center">Không có danh mục nào</div>
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