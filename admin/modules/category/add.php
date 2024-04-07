<!-- Đăng ký tài khoản -->
<?php
if (!defined("_CODE")) {
    die ("Access Denied !");
}
require_once '../class/category.php';
$category = new Category($conn);

$data = [
    'pageTitle' => 'Thêm danh mục mới'
];
if (isPost()) {
    $filterAll = filter();
    $error = [];//Mảng chữa lỗi
    //Validate title: bắt buộc phải nhập
    if (empty ($filterAll['name'])) {
        $error['name']['required'] = 'Tên danh mục không được để trống.';
    } else {
        $title = $filterAll['name'];
        $sql = "SELECT * FROM product WHERE name = '$name'";
        if (getRows($sql) > 0) {
            $error['name']['unique'] = 'Danh mục đã tồn tại.';
        }
    }
    if (empty ($error)) {
        $category->addCategory($dataInsert);
    } else {
        setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu');
        setFlashData('msg_type', 'danger');
        setFlashData('error', $error);
        setFlashData('old', $filterAll);
        redirect('?module=category&action=add');
    }
}
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
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Thêm danh mục mới</h1>
                            </div>
                            <?php
                            if (!empty ($msg)) {
                                getMSG($msg, $msg_type);
                            }
                            ?>
                            <form class="user" method="post">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <p>Tên danh mục:</p>
                                            <input type="text" class="form-control form-control-user" name="name" value="<?php
                                            echo old('name', $old)
                                                ?>">
                                            <?php
                                            echo form_error('name', '<span class= "error">', '</span>', $error);

                                            ?>
                                        </div>




                                    </div>


                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <button type="submit" class="mg-btn btn btn-primary btn-block">
                                            Thêm
                                        </button>
                                    </div>
                                    <div class="col-sm-6"><a href="?module=category&action=list"
                                            class="mg-btn btn btn-success btn-block">Quay lại</a></div>
                                </div>




                            </form>
                            <hr>

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