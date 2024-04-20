<?php
if (!defined("_CODE")) {
    die ("Access Denied !");
}
require_once '../class/product.php';

$data = [
    'pageTitle' => 'Thêm sản phẩm mới'
];
$userRole = getSession('admin_role');
$product = new Product($conn);

if (isPost()) {
    $filterAll = filter();
    $error = [];
    //Validate name: bắt buộc phải nhập
    if (empty ($filterAll['name'])) {
        $error['name']['required'] = 'Tên sản phẩm không được để trống.';
    } else {
        $name = $filterAll['name'];
        $sql = "SELECT * FROM product WHERE name = '$name'";
        if (getRows($sql) > 0) {
            $error['name']['unique'] = 'Sản phẩm đã tồn tại.';
        }
    }
    //Validate giá: bắt buộc phải nhập, đúng định dạng số nguyên
    if (empty ($filterAll['price'])) {
        $error['price']['required'] = 'Giá không được để trống.';
    } else {
        if (!isNumberInt($filterAll['price'])) {
            $error['price']['isNumberInt'] = 'Giá phải là số nguyên.';
        }

    }
    //Validate mô tả: bắt buộc phải nhập, < 20 ký tự
    if (empty ($filterAll['description'])) {
        $error['description']['required'] = 'Mô tả không được để trống.';
    } else {
        if (strlen($filterAll['description']) < 20) {
            $error['description']['min'] = 'Mô tả phải có ít nhất 50 ký tự.';
        }
    }

    if (empty($error)) {
        $product->addProduct($dataInsert);
    } else {
        setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu');
        setFlashData('msg_type', 'danger');
        setFlashData('error', $error);
        setFlashData('old', $filterAll);
        redirect('?module=products&action=add');
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
                                <h1 class="h4 text-gray-900 mb-4">Thêm sản phẩm mới</h1>
                            </div>
                            <?php
                            if (!empty ($msg)) {
                                getMSG($msg, $msg_type);
                            }
                            ?>
                            <form class="products" method="post">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <p>Tên sản phẩm:</p>
                                            <input type="text" class="form-control form-control-user" name="name" value="<?php
                                            echo old('name', $old)
                                                ?>">
                                            <?php
                                            echo form_error('name', '<span class= "error">', '</span>', $error);

                                            ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="category">Chọn danh mục:</label>
                                            <select id="category_id" name="category_id" class="form-control">
                                                <?php
                                                $sql = "SELECT id, name FROM category";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute();
                                                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($categories as $category) {
                                                    echo "<option value='" . $category['id'] . "'>" . $category['name'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <p>Giá sản phẩm:</p>
                                            <input type="text" class="form-control form-control-user" name="price"
                                                value="<?php
                                                echo old('price', $old)
                                                    ?>">
                                            <?php
                                            echo form_error('price', '<span class= "error">', '</span>', $error);
                                            ?>
                                        </div>

                                        <div class="form-group">
                                            <p>Ảnh bìa:</p>
                                            <input type="file" class="form-control form-control-user" name="thumbnail"
                                                onchange="readURL(this);">
                                            <img id="ShowImage" />
                                        </div>
                                        <div class="form-group">
                                            <p>Ảnh:</p>
                                            <input type="file" class="form-control form-control-user"
                                                name="images_path[]" id="uploadInput" multiple>
                                            <div id="imagePreview"></div>
                                        </div>
                                        <style>
                                            .img-preview {
                                                width: 200px;
                                                padding: 20px;
                                            }
                                        </style>

                                        <script>
                                            function readURL(input) {
                                                if(input.files && input.files[0]) {
                                                    var reader = new FileReader();
                                                    reader.onload = function (e) {
                                                        $('#ShowImage')
                                                            .attr('src', e.target.result)
                                                            .width(150)
                                                            .height(200);
                                                    };
                                                    reader.readAsDataURL(input.files[0]);
                                                }
                                            }
                                            document.getElementById('uploadInput').addEventListener('change', function () {
                                                var files = this.files;
                                                var imagePreview = document.getElementById('imagePreview');
                                                imagePreview.innerHTML = ''; // Xóa hình ảnh trước đó
                                                for(var i = 0; i < files.length; i++) {
                                                    var file = files[i];
                                                    var imageType = /image.*/;
                                                    if(!file.type.match(imageType)) {
                                                        continue;
                                                    }
                                                    var img = document.createElement('img');
                                                    img.classList.add('img-preview');
                                                    img.file = file;
                                                    imagePreview.appendChild(img);
                                                    var reader = new FileReader();
                                                    reader.onload = (function (aImg) {
                                                        return function (e) {
                                                            aImg.src = e.target.result;
                                                        };
                                                    })(img);
                                                    reader.readAsDataURL(file);
                                                }
                                            });
                                        </script>
                                        <div class="form-group">
                                            <p>Mô tả:</p>
                                            <input type="text" class="form-control form-control-user" name="description"
                                                value="<?php
                                                echo old('description', $old)
                                                    ?>">
                                            <?php
                                            echo form_error('description', '<span class= "error">', '</span>', $error);

                                            ?>
                                        </div>


                                    </div>

                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <button type="submit" class="mg-btn btn btn-primary btn-block" name="submit">
                                            Thêm
                                        </button>
                                    </div>
                                    <div class="col-sm-6"><a href="?module=products&action=list"
                                            class="mg-btn btn btn-success btn-block">Quay lại</a></div>
                                </div>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="?module=authen&action=forgot">Quên mật khẩu?</a>
                            </div>
                            <div class=" text-center">
                                <a class="small" href="?module=authen&action=login">Đã có tài khoản? Hãy
                                    đăng nhập!</a>
                            </div>
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