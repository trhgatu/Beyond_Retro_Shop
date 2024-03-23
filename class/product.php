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
        $productId = $this->conn->lastInsertId();
        $dataImagesInsert = [
            'product_id' => $productId,
            'images_path' => implode(",", $filterAll['images_path']),
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $insertImageStatus = insert('galery', $dataImagesInsert);
        $insertStatus = insert('product', $dataInsert);
        if ($insertStatus && $insertImageStatus) {
            return true; // Thêm sản phẩm thành công
        } else {
            return false; // Thêm ảnh thất bại
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
            $productDetail = getRaw("SELECT * FROM product WHERE id = $productId");
            if ($productDetail > 0) {
                //Thực hiện xóa
                $deleteGalery = delete('galery',"product_id = $productId");
                if($deleteGalery)
                {
                    $deleteProduct = delete('product', "id = $productId");
                    if ($deleteProduct) {
                        return true;
                    }
                    return false;
                }
            } else {
                return false;
            }
        }
    }
}