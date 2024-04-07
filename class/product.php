<?php
class Product
{
    private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function addProduct($dataInsert)
    {
        $filterAll = filter();
        $dataInsert = [
            'name' => $filterAll['name'],
            'category_id' => $filterAll['category_id'],
            'price' => $filterAll['price'],
            'thumbnail' => $filterAll['thumbnail'],
            'description' => $filterAll['description'],
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $insertStatus = insert('product', $dataInsert);
        if ($insertStatus) {
            $productId = $this->conn->lastInsertId();
            $dataImagesInsert = [
                'product_id' => $productId,
                'images_path' => implode(",", $filterAll['images_path']),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $insertImageStatus = insert('galery', $dataImagesInsert);
            if ($insertImageStatus) {
                setFlashData('msg', 'Thêm sản phẩm mới thành công.');
                setFlashData('msg_type', 'success');
                redirect('?module=products&action=list');
            } else {
                setFlashData('msg', 'Thêm sản phẩm thất bại, vui lòng thử lại.');
                setFlashData('msg_type', 'danger');
            }
            redirect('?module=products&action=add');
        } else {
            setFlashData('msg', 'Thêm sản phẩm thất bại, vui lòng thử lại.');
            setFlashData('msg_type', 'danger');
        }

    }
    public function updateProduct($dataUpdate)
    {
        $filterAll = filter();
        $productId = $filterAll['id'];
        $dataUpdate = [
            'name' => $filterAll['name'],
            'category_id' => $filterAll['category_id'],
            'price' => $filterAll['price'],
            'thumbnail' => $filterAll['thumbnail'],
            'description' => $filterAll['description'],
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        if (!empty($filterAll['thumbnail'])) {
            $dataUpdate['thumbnail'] = $filterAll['thumbnail'];
        }
        if (!empty($filterAll['images_path'])) {
            $dataImagesUpdate = [
                'product_id' => $productId,
                'images_path' => implode(",", $filterAll['images_path']),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $conditionImg = "product_id = $productId";
            $updateImagesStatus = update('galery', $dataImagesUpdate, $conditionImg);
        }
        $condition = "id = $productId";
        $updateStatus = update('product', $dataUpdate, $condition);

        if ($updateStatus && $updateImagesStatus == true) {
            return true;
        } else {
            return false;
        }
    }
    public function listProduct()
    {
        $products = array();

        $query = "SELECT * FROM product";
        $result = $this->conn->query($query);

        // Kiểm tra xem truy vấn có dữ liệu trả về không
        if ($result && $result->rowCount() > 0) {
            // Lặp qua các hàng kết quả và thêm thông tin của mỗi sản phẩm vào mảng
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $products[] = $row;
            }
        }
        // Trả về mảng chứa thông tin về tất cả các sản phẩm
        return $products;
    }
    public function deleteProduct()
    {
        $filterAll = filter();
        if (!empty($filterAll['id'])) {
            $productId = $filterAll['id'];
            // Lấy chi tiết sản phẩm
            $productDetail = getRaw("SELECT * FROM product WHERE id = $productId");
            if ($productDetail > 0) {
                // Xóa các bản ghi trong bảng 'galery' liên kết với sản phẩm
                $deleteGalery = delete('galery', "product_id = $productId");
                if ($deleteGalery) {
                    // Xóa sản phẩm
                    $deleteProduct = delete('product', "id = $productId");
                    if ($deleteProduct) {
                        setFlashData('msg', 'Xóa sản phẩm thành công.');
                        setFlashData('msg_type', 'success');
                    }
                } else {
                    return false;
                }
            } else {
                setFlashData('msg', 'Sản phẩm không tồn tại trong hệ thống.');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Liên kết không tồn tại.');
            setFlashData('msg_type', 'danger');
        }
        redirect('?module=products&action=list');
    }
    public function getImagesByProductId($product_id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT images_path FROM galery WHERE product_id = :product_id");
            $stmt->bindParam(':product_id', $product_id);
            $stmt->execute();
            $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $images;
        } catch (PDOException $e) {
            // Xử lý ngoại lệ nếu cần thiết
            echo "Lỗi: " . $e->getMessage();
            return false;
        }
    }



}