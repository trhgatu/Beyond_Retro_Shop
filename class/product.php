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
        $error = [];
        //Validate title: bắt buộc phải nhập
        if (empty($filterAll['name'])) {
            $error['name']['required'] = 'Tên sản phẩm không được để trống.';
        } else {
            if (strlen($filterAll['name']) < 5) {
                $error['name']['min'] = 'Tên sản phẩm phải có ít nhất 10 ký tự.';
            }
        }
        //Validate giá: bắt buộc phải nhập, đúng định dạng số nguyên
        if (empty($filterAll['price'])) {
            $error['price']['required'] = 'Giá không được để trống.';
        } else {
            if (!isNumberInt($filterAll['price'])) {
                $error['price']['isNumberInt'] = 'Giá phải có giá trị là số nguyên.';
            }
        }
        //Validate mô tả: bắt buộc phải nhập, > 50 ký tự
        if (empty($filterAll['description'])) {
            $error['description']['required'] = 'Mô tả không được để trống.';
        } else {
            if (strlen($filterAll['description']) < 20) {
                $error['description']['min'] = 'Mô tả phải có ít nhất 20 ký tự.';
            }
        }
        $filterAll = filter();
        $productId = $filterAll['id'];

        $dataUpdateProduct = [
            'name' => $filterAll['name'],
            'category_id' => $filterAll['category_id'],
            'price' => $filterAll['price'],
            'description' => $filterAll['description'],
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if (!empty($filterAll['thumbnail'])) {
            $dataUpdateProduct['thumbnail'] = $filterAll['thumbnail'];
        }
        $dataUpdateImages = [
            'product_id' => $productId,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if (!empty($filterAll['images_path'])) {
            $dataUpdateImages['images_path'] = implode(",", $filterAll['images_path']);
        }

        $condition = "id = $productId";
        $conditionImg = "product_id = $productId";

        $updateStatus = update('product', $dataUpdateProduct, $condition);
        $updateImagesStatus = update('galery', $dataUpdateImages, $conditionImg);
        if (empty($error)) {
            if ($updateStatus && $updateImagesStatus) {
                setFlashData('msg', 'Cập nhật thông tin sản phẩm thành công.');
                setFlashData('msg_type', 'success');
                redirect('?module=products&action=list');
            } else {
                setFlashData('msg', 'Cập nhật thông tin sản phẩm thất bại, vui lòng thử lại.');
                setFlashData('msg_type', 'danger');
            }
            redirect('?module=products&action=edit');
        } else {
            setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu');
            setFlashData('msg_type', 'danger');
            setFlashData('error', $error);
            setFlashData('old', $filterAll);
            redirect('?module=products&action=edit&id=' . $productId);
        }
        redirect('?module=products&action=edit&id=' . $productId);
    }

    public function listProduct()
    {
        $products = array();

        $query = "SELECT * FROM product";
        $result = $this->conn->query($query);
        if ($result && $result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $products[] = $row;
            }
        }
        return $products;
    }
    public function listProductPagination()
    {
        $items_per_page = 6;

        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $products = array();
        $start = ($page - 1) * $items_per_page;

        $sql = "SELECT * FROM product LIMIT :start, :items_per_page";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':items_per_page', $items_per_page, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $products[] = $row;
            }
        }
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
    public function getImagesByProductId($productId)
    {
        try {
            $stmt = $this->conn->prepare("SELECT images_path FROM galery WHERE product_id = :product_id");
            $stmt->bindParam(':product_id', $productId);
            $stmt->execute();
            $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $images;
        } catch (PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
            return false;
        }
    }

    public function getByProductId($productId)
    {
        $productDetail = getRaw("SELECT * FROM product WHERE id = $productId");
        if ($productDetail) {
            return $productDetail;
        } else {
            return null;
        }
    }
    public function searchProduct($query)
    {
        $products = array();
        $search_query = '%' . $query . '%';
        $sql = "SELECT * FROM product WHERE name LIKE :search_query";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':search_query', $search_query, PDO::PARAM_STR);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($results) > 0) {
                $products = $results;
            }
        } catch (PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
        }
        return $products;
    }

}