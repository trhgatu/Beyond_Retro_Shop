<?php
class User
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function addUser($dataInsert)
    {
        $filterAll = filter();
        $activeToken = sha1(uniqid() . time());
        $dataInsert = [
            'fullname' => $filterAll['fullname'],
            'avatar' => $filterAll['avatar'],
            'email' => $filterAll['email'],
            'phone_number' => $filterAll['phone_number'],
            'password' => password_hash($filterAll['password'], PASSWORD_DEFAULT),
            'role_id' => $filterAll['role_id'],
            'status' => $filterAll['status'],
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $insertStatus = insert('user', $dataInsert);
        if ($insertStatus) {
            setFlashData('msg', 'Thêm người dùng mới thành công.');
            setFlashData('msg_type', 'success');
            redirect('?module=users&action=list');
        } else {
            setFlashData('msg', 'Thêm người dùng thất bại, vui lòng thử lại.    ');
            setFlashData('msg_type', 'danger');
        }
        redirect('?module=users&action=add');
    }
    public function deleteUser()
    {
        $filterAll = filter();
        if (!empty ($filterAll['id'])) {
            $userId = $filterAll['id'];
            $userDetail = getRaw("SELECT * FROM user WHERE id = $userId");
            if ($userDetail > 0) {
                //Thực hiện xóa
                $deleteToken = delete('tokenlogin', "user_id = $userId");
                if ($deleteToken) {
                    //Xóa user
                    $deleteUser = delete('user', "id = $userId");
                    if ($deleteUser) {
                        setFlashData('msg', 'Xóa người dùng thành công.');
                        setFlashData('msg_type', 'success');
                    }
                }
            } else {
                setFlashData('msg', 'Người dùng không tồn tại trong hệ thống.');
                setFlashData('msg_type', 'danger');
            }

        } else {
            setFlashData('msg', 'Liên kết không tồn tại.');
            setFlashData('msg_type', 'danger');
        }
        redirect('?module=users&action=list');
    }
    public function listUser()
    {
        $users = array();

        $query = "SELECT * FROM user";
        $result = $this->conn->query($query);

        // Kiểm tra xem truy vấn có dữ liệu trả về không
        if ($result && $result->rowCount() > 0) {
            // Lặp qua các hàng kết quả và thêm thông tin của mỗi sản phẩm vào mảng
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $users[] = $row;
            }
        }
        // Trả về mảng chứa thông tin về tất cả các sản phẩm
        return $users;
    }
    public function showProfile(){

    }
}
