<?php
class Product
{
    private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function uploadGallery($uploadedImages)
    {
        $uploadedImagePaths = [];
        foreach ($uploadedImages['tmp_name'] as $key => $tmp_name) {
            try {
                if ($uploadedImages['error'][$key] !== UPLOAD_ERR_OK) {
                    throw new Exception('Upload error');
                }
                if ($uploadedImages['size'][$key] > 1000000) {
                    throw new Exception('File too large');
                }

                $mime_types = ['image/png', 'image/jpeg', 'image/gif'];
                $file_info = finfo_open(FILEINFO_MIME_TYPE);
                $mime_type = finfo_file($file_info, $tmp_name);

                if (!in_array($mime_type, $mime_types)) {
                    throw new Exception('Invalid file type');
                }

                $pathinfo = pathinfo($uploadedImages['name'][$key]);
                $fname = 'product_image_' . $key;
                $extension = $pathinfo['extension'];
                $dest = '../images/products/gallery/' . $fname . '.' . $extension;
                $i = 1;
                while (file_exists($dest)) {
                    $dest = '../images/products/gallery/' . $fname . "-$i." . $extension;
                    $i++;
                }
                if (move_uploaded_file($tmp_name, $dest)) {
                    $uploadedImagePaths[] = $dest;
                } else {
                    throw new Exception('Unable to move file');
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
        return $uploadedImagePaths;
    }

    public function uploadThumbnail()
    {
        if (empty($_FILES['thumbnail'])) {
            throw new Exception('Invalid upload');
        }
        switch ($_FILES['thumbnail']['error']) {
            case UPLOAD_ERR_OK;
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new Exception('No file upload');
            default:
                throw new Exception('An error occured');
        }
        if ($_FILES['thumbnail']['size'] > 1000000) {
            throw new Exception('File too large');
        }
        $mime_types = ['image/png', 'image/jpeg', 'image/gif'];
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($file_info, $_FILES['thumbnail']['tmp_name']);

        if (!in_array($mime_type, $mime_types)) {
            throw new Exception('invalid file type');
        }
        $pathinfo = pathinfo($_FILES['thumbnail']['name']);
        $fname = 'thumbnail';
        $extension = $pathinfo['extension'];
        $dest = '../images/products/thumbnail/' . $fname . '.' . $extension;
        $i = 1;
        while (file_exists($dest)) {
            $dest = '../images/products/thumbnail/' . $fname . "-$i." . $extension;
            $i++;
        }
        return $dest;
    }
    public function addProduct($filterAll, $dest, $upload_dest)
    {
        $dataInsert = [
            'name' => $filterAll['name'],
            'category_id' => $filterAll['category_id'],
            'price' => $filterAll['price'],
            'thumbnail' => basename($dest),
            'description' => $filterAll['description'],
            'created_at' => date('Y-m-d H:i:s'),
            'flag' => $filterAll['flag'],
        ];
        $insertStatus = insert('product', $dataInsert);
        if ($insertStatus) {
            $productId = $this->conn->lastInsertId();
            foreach ($upload_dest as $image_path) {
                $dataImagesInsert = [
                    'product_id' => $productId,
                    'images_path' => basename($image_path),
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $insertImageStatus = insert('galery', $dataImagesInsert);
            }
            setFlashData('msg', 'Thêm sản phẩm mới thành công.');
            setFlashData('msg_type', 'success');
            redirect('?module=products&action=list');
        } else {
            setFlashData('msg', 'Thêm sản phẩm thất bại, vui lòng thử lại.');
            setFlashData('msg_type', 'danger');
            redirect('?module=products&action=add');
        }
    }

    public function updateProduct($filterAll, $dest, $upload_dest)
    {
        $productId = $filterAll['id'];
        $flag = isset($filterAll['flag']) ? 1 : 0;
        $dataUpdateProduct = [
            'name' => $filterAll['name'],
            'category_id' => $filterAll['category_id'],
            'price' => $filterAll['price'],
            'description' => $filterAll['description'],
            'flag' => $flag,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        if (!empty($dest)) {
            $dataUpdateProduct['thumbnail'] = basename($dest);
        }

        $condition = "id = $productId";
        $updateStatus = update('product', $dataUpdateProduct, $condition);

        if ($updateStatus) {
            // Xóa ảnh cũ trong thư viện nếu có ảnh mới được upload
            if (!empty($upload_dest)) {
                $deleteCondition = "product_id = $productId";
                delete('galery', $deleteCondition);
                foreach ($upload_dest as $image_path) {
                    if (!in_array(basename($image_path), $this->getImagesByProductId($productId))) {
                        $dataUpdateImage = [
                            'product_id' => $productId,
                            'images_path' => basename($image_path),
                            'created_at' => date('Y-m-d H:i:s'),
                        ];
                        insert('galery', $dataUpdateImage);
                    }
                }
            }

            setFlashData('msg', 'Cập nhật thông tin sản phẩm thành công.');
            setFlashData('msg_type', 'success');
            redirect('?module=products&action=list');
        } else {
            setFlashData('msg', 'Cập nhật thông tin sản phẩm thất bại, vui lòng thử lại.');
            setFlashData('msg_type', 'danger');
            redirect('?module=products&action=edit');
        }
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
            $productDetail = getRaw("SELECT * FROM product WHERE id = $productId");
            if ($productDetail > 0) {
                $deleteGalery = delete('galery', "product_id = $productId");
                $deleteOrderDetail = delete('order_details', "product_id = $productId");
                if ($deleteOrderDetail) {
                    if ($deleteGalery) {
                        $deleteProduct = delete('product', "id = $productId");
                        if ($deleteProduct) {
                            setFlashData('msg', 'Xóa sản phẩm thành công.');
                            setFlashData('msg_type', 'success');
                        } else {
                            setFlashData('msg', 'Xóa sản phẩm thất bại, vui lòng thử lại!');
                            setFlashData('msg_type', 'danger');
                        }
                    } else {
                        setFlashData('msg', "Không thể xóa hình ảnh");
                        setFlashData('msg_type', 'danger');
                    }
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
    public function filterProductByPrice($price_range)
    {
        if (strpos($price_range, '+') !== false) {
            $min_price = 1500000;
            $max_price = PHP_INT_MAX;
        } else {
            $price_parts = explode('-', $price_range);
            $min_price = isset($price_parts[0]) ? $price_parts[0] : 0;
            $max_price = isset($price_parts[1]) ? $price_parts[1] : PHP_INT_MAX;
        }
        $sql = "SELECT * FROM product WHERE price BETWEEN :min_price AND :max_price";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':min_price', $min_price);
        $stmt->bindParam(':max_price', $max_price);
        $stmt->execute();

        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = $row;
        }
        return $products;
    }
    // Trong file product.php
    public function filterProductByCategory($category_id)
    {
        $sql = "SELECT * FROM product WHERE category_id = :category_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();

        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = $row;
        }
        return $products;
    }







}