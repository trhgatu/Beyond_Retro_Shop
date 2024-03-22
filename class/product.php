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
                return true; // Thêm sản phẩm thành công
            } else {
                return false; // Thêm ảnh thất bại
            }
        } else {
            return false; // Thêm sản phẩm thất bại
        }

    }
}