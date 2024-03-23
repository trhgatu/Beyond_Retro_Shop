<?php
if (!defined("_CODE")) {
    die ("Access Denied !");
}
require_once '../admin/includes/connect.php';
require_once '../class/product.php';
$data = [
    'pageTitle' => 'Danh sách sản phẩm'
];
//Kiểm tra trạng thái đăng nhập
$product = new Product($conn);
$listProducts = $product->listProduct();

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$error = getFlashData('error');
$old = getFlashData('old');
?>

<div id="wrapper">
    <?php
    layouts('style', $data);
    layouts('sidebar', $data);
    ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php
            layouts('header', $data);
            ?>
            <div class="container-fluid">
                <div class="card shadow mb-4" style="max-width: 1240px">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Danh sách sản phẩm
                            <a href="?module=products&action=add" class="btn btn-success btn-sm"
                                style="float:right">Thêm sản phẩm mới<i class="fa-solid fa-plus"></i></a>
                        </h6>

                    </div>
                    <?php
                    if (!empty ($msg)) {
                        getMSG($msg, $msg_type);
                    }
                    ?>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <th>STT</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Danh mục</th>
                                    <th>Giá</th>
                                    <th>Ảnh bìa </th>
                                    <th>Thư viện ảnh</th>
                                    <th>Mô tả sản phẩm </th>
                                    <th width="5%">Sửa</th>
                                    <th width="5%">Xóa</th>

                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty ($listProducts)):
                                        $count = 0; //STT
                                        foreach ($listProducts as $item):
                                            $count++;
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $count; ?>
                                                </td>
                                                <td>
                                                    <?php echo $item['name'] ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $category_id = $item['category_id']; // ID danh mục của sản phẩm
                                                    $category_name = "";
                                                    // Truy vấn để lấy tên danh mục từ bảng danh mục dựa trên category_id của sản phẩm
                                                    $stmt = $conn->prepare("SELECT name FROM category WHERE id = :category_id");
                                                    $stmt->bindParam(':category_id', $category_id);
                                                    $stmt->execute();
                                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                    if ($result) {
                                                        $category_name = $result['name'];
                                                    }
                                                    echo $category_name;
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php echo $item['price'] ?>
                                                </td>
                                                <td>
                                                    <img src="../admin/modules/products/images/<?php echo $item['thumbnail'] ?>"
                                                        style="max-width: 180px;">
                                                </td>
                                                <td>
                                                    <?php
                                                    $product_id = $item['id']; // Lấy ID của sản phẩm
                                                    $conn = new PDO("mysql:host=localhost;dbname=beyond_retro", "root", "mysql");
                                                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                                    // Truy vấn để lấy tất cả các hình ảnh từ bảng galery dựa trên product_id của sản phẩm
                                                    $stmt = $conn->prepare("SELECT images_path FROM galery WHERE product_id = :product_id");
                                                    $stmt->bindParam(':product_id', $product_id);
                                                    $stmt->execute();
                                                    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($images as $image) {
                                                        $image_paths = explode(",", $image['images_path']);
                                                        foreach ($image_paths as $image_path) {
                                                            // Hiển thị mỗi ảnh trong thẻ <img>
                                                            echo "<img src='../admin/modules/products/images/$image_path' style='max-width: 180px;'> ";
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php echo $item['description'] ?>
                                                </td>
                                                <td><a href="<?php echo _WEB_HOST; ?>?module=products&action=edit&id=<?php echo $item['id'] ?>"
                                                        class="btn btn-warning btn-sm"><i
                                                            class="fa-solid fa-pen-to-square"></i></a>
                                                </td>
                                                <td><a href="<?php echo _WEB_HOST; ?>?module=products&action=delete&id=<?php echo $item['id'] ?>"
                                                        onclick="return confirm('Bạn có muốn xóa?')"
                                                        class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a></td>
                                            </tr>
                                            <?php
                                        endforeach;
                                    else:
                                        ?>
                                        <tr>
                                            <td colspan="7">
                                                <div class="alert alert-danger text-center">Không có sản phẩm nào</div>
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
        layouts('footer', $data);
        ?>
    </div>

</div>