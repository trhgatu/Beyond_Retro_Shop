<?php
class Category
{
    private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function addCategory($dataInsert)
    {
        $filterAll = filter();
        $dataInsert = [
            'name' => $filterAll['name'],
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $insertStatus = insert('category', $dataInsert);
        if ($insertStatus) {
            setFlashData('msg', 'Thêm danh mục mới thành công.');
            setFlashData('msg_type', 'success');
            redirect('?module=category&action=list');
        } else {
            setFlashData('msg', 'Thêm danh mục thất bại, vui lòng thử lại.');
            setFlashData('msg_type', 'danger');
        }
        redirect('?module=category&action=add');
    }

    public function deleteCategory()
    {
        $filterAll = filter();
        if (!empty($filterAll['id'])) {
            $categoryId = $filterAll['id'];
            $categoryDetail = getRaw("SELECT * FROM category WHERE id = $categoryId");
            if ($categoryDetail > 0) {
                $deleteCategory = delete('category', "id = $categoryId");
                if ($deleteCategory) {
                    setFlashData('msg', 'Xóa danh mục thành công.');
                    setFlashData('msg_type', 'success');
                }
            } else {
                setFlashData('msg', 'Danh mục không tồn tại trong hệ thống.');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Liên kết không tồn tại.');
            setFlashData('msg_type', 'danger');
        }
        redirect('?module=category&action=list');
    }

    public function listCategories()
    {
        $categories = array();

        $query = "SELECT * FROM category";
        $result = $this->conn->query($query);

        if ($result && $result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $categories[] = $row;
            }
        }
        return $categories;

    }





}