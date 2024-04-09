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
        if (!empty($filterAll['id'])) {
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
        return $users;
    }
    public function showProfile()
    {
        if (isLogin()) {
            // Lấy token từ session
            $token = getSession('tokenlogin');

            // Truy vấn cơ sở dữ liệu để lấy thông tin user_id từ token
            $query = "SELECT user_id FROM tokenlogin WHERE token = :token";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':token', $token);
            $stmt->execute();

            // Kiểm tra xem có dữ liệu trả về không
            if ($stmt->rowCount() > 0) {
                // Lấy user_id từ kết quả truy vấn
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $user_id = $row['user_id'];

                // Truy vấn cơ sở dữ liệu để lấy thông tin người dùng từ user_id
                $query_user = "SELECT * FROM user WHERE id = :user_id";
                $stmt_user = $this->conn->prepare($query_user);
                $stmt_user->bindParam(':user_id', $user_id);
                $stmt_user->execute();

                // Kiểm tra xem có dữ liệu trả về không
                if ($stmt_user->rowCount() > 0) {
                    // Trả về thông tin người dùng nếu tìm thấy
                    return $stmt_user->fetch(PDO::FETCH_ASSOC);
                }
            }
        }
        return null; // Trả về null nếu không tìm thấy thông tin người dùng
    }



}
