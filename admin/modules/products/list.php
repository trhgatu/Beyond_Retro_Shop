<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
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
    layout_admin('style', $data);
    layout_admin('sidebar', $data);
    ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php
            layout_admin('header', $data);
            ?>
            <div class="container-fluid">
                <div class="card mb-4" style="max-width: 1240px;box-shadow: rgba(9, 30, 66, 0.25) 0px 4px 8px -2px, rgba(9, 30, 66, 0.08) 0px 0px 0px 1px;">
                    <div class="card-header py-3" style="background-color:#CC181E">
                        <h5 class="m-0 font-weight-bold text-white">
                            Danh sách sản phẩm
                            <a href="?module=products&action=add" class="btn btn-light btn-sm"
                                style="float:right">Thêm sản phẩm mới <i class="fa-solid fa-plus"></i></a>
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
                                    if (!empty($listProducts)):
                                        $count = 0; //STT
                                        foreach ($listProducts as $item):
                                            $count++;
                                            ?>
                                            <tr>
                                                <td>
                                                   <p><?php echo $count; ?></p>
                                                </td>
                                                <td>
                                                    <p><?php echo $item['name'] ?></p>
                                                </td>
                                                <td>
                                                    <?php
                                                    $category_id = $item['category_id'];
                                                    $category_name = "";
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
                                                    <p><?php echo number_format($item['price'], 0, ',', '.') ?> VNĐ</p>
                                                </td>
                                                <td>
                                                    <img src="../images/products/thumbnail/<?php echo $item['thumbnail'] ?>"
                                                        style="max-width: 170px;">
                                                </td>
                                                <td>
                                                    <?php
                                                    $product_id = $item['id'];
                                                    $product = new Product($conn);
                                                    $images = $product->getImagesByProductId($product_id);
                                                    if ($images) {
                                                        foreach ($images as $image) {
                                                            $image_paths = explode(",", $image['images_path']);
                                                            foreach ($image_paths as $image_path) {
                                                                // Hiển thị mỗi ảnh trong thẻ <img>
                                                                echo "<img src='../images/products/gallery/$image_path' style='width: 40%;'> ";
                                                            }
                                                        }
                                                    } else {
                                                        echo "Không có hình ảnh cho sản phẩm này.";
                                                    }
                                                    ?>

                                                </td>
                                                <td>
                                                    <p><?php echo $item['description'] ?></p>
                                                </td>
                                                <td><a href="<?php echo _WEB_HOST_ADMIN; ?>?module=products&action=edit&id=<?php echo $item['id'] ?>"
                                                        class="btn btn-warning btn-sm"><i
                                                            class="fa-solid fa-pen-to-square"></i></a>
                                                </td>
                                                <td><a href="<?php echo _WEB_HOST_ADMIN; ?>?module=products&action=delete&id=<?php echo $item['id'] ?>"
                                                        onclick="return confirm('Bạn có muốn xóa?')"
                                                        class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a></td>
                                            </tr>
                                            <?php
                                        endforeach;
                                    else:
                                        ?>
                                        <tr>
                                            <td colspan="9">
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
        layout_admin('footer', $data);
        ?>
    </div>

</div>
<style>
    p {
        font-size: 13px;
    }
    th{
        font-size: 14px;
    }
</style>