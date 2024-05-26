<?php
class User
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function add($filterAll, $dest)
    {
        $filterAll = filter();
        $activeToken = sha1(uniqid() . time());
        $dataInsert = [
            'fullname' => $filterAll['fullname'],
            'avatar' => basename($dest),
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
            setFlashData('msg', 'Thêm người dùng thất bại, vui lòng thử lại.');
            setFlashData('msg_type', 'danger');
            redirect('?module=users&action=add');
        }
    }


    public function delete()
    {
        $filterAll = filter();
        if (!empty($filterAll['id'])) {
            $userId = $filterAll['id'];
            $userDetail = getRaw("SELECT * FROM user WHERE id = $userId");
            if ($userDetail > 0) {
                $deleteOrderDetails = delete('order_details', "order_id IN (SELECT id FROM orders WHERE user_id = $userId)");
                $deleteOrder = delete('orders', "user_id = $userId");
                $deleteToken = delete('tokenlogin', "user_id = $userId");
                $deleteAdress = delete('addresses', "user_id = $userId");
                if ($deleteOrder && $deleteOrderDetails && $deleteToken && $deleteAdress) {
                    $deleteUser = delete('user', "id = $userId");
                    if ($deleteUser) {
                        setFlashData('msg', 'Xóa người dùng thành công.');
                        setFlashData('msg_type', 'success');
                        redirect('?module=users&action=list');
                    } else {
                        setFlashData('msg', 'Không thể xóa người dùng, vui lòng thử lại.');
                        setFlashData('msg_type', 'danger');
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
    public function list()
    {
        $users = array();
        $query = "SELECT * FROM user";
        $result = $this->conn->query($query);
        if ($result && $result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $row['online'] = $this->isUserorAdminOnline($row['id']);
                $users[] = $row;
            }
        }
        return $users;
    }
    public function edit($filterAll, $dest){
        $userId = $filterAll['id'];
        $activeToken = sha1(uniqid() . time());
        $dataUpdate = [
            'fullname' => $filterAll['fullname'],
            'avatar' => basename($dest),
            'email' => $filterAll['email'],
            'phone_number' => $filterAll['phone_number'],
            'status' => $filterAll['status'],
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        if (!empty($filterAll['password'])) {
            $dataUpdate['password'] = password_hash($filterAll['password'], PASSWORD_DEFAULT);
        }

        $condition = "id = $userId";
        $UpdateStatus = update('user', $dataUpdate, $condition);
        if ($UpdateStatus) {
            setFlashData('msg', 'Sửa người dùng thành công.');
            setFlashData('msg_type', 'success');
            redirect('?module=users&action=list');
        } else {
            setFlashData('msg', 'Sửa người dùng thất bại, vui lòng thử lại.');
            setFlashData('msg_type', 'danger');
        }
    }
    public function uploadAvatar()
    {
        if (empty($_FILES['avatar'])) {
            throw new Exception('Invalid upload');
        }
        switch ($_FILES['avatar']['error']) {
            case UPLOAD_ERR_OK;
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new Exception('No file upload');
            default:
                throw new Exception('An error occured');
        }
        if ($_FILES['avatar']['size'] > 1000000) {
            throw new Exception('File too large');
        }
        $mime_types = ['image/png', 'image/jpeg', 'image/gif'];
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($file_info, $_FILES['avatar']['tmp_name']);

        if (!in_array($mime_type, $mime_types)) {
            throw new Exception('invalid file type');
        }
        $pathinfo = pathinfo($_FILES['avatar']['name']);
        $fname = 'avatar';
        $extension = $pathinfo['extension'];
        $dest = '../images/avatar/' . $fname . '.' . $extension;
        $i = 1;
        while (file_exists($dest)) {
            $dest = '../images/avatar/' . $fname . "-$i." . $extension;
            $i++;
        }
        return $dest;
    }

    public function showProfile()
    {
        if (isUserLogin()) {
            $token = getSession('tokenlogin');
            $query = "SELECT user_id FROM tokenlogin WHERE token = :token";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $user_id = $row['user_id'];

                $query_user = "SELECT * FROM user WHERE id = :user_id";
                $stmt_user = $this->conn->prepare($query_user);
                $stmt_user->bindParam(':user_id', $user_id);
                $stmt_user->execute();

                if ($stmt_user->rowCount() > 0) {
                    return $stmt_user->fetch(PDO::FETCH_ASSOC);
                }
            }
        }
        return null;
    }
    function isUserOrAdminOnline($id)
    {
        global $conn;
        $queryUser = "SELECT COUNT(*) FROM tokenlogin WHERE user_id = :id";
        $queryAdmin = "SELECT COUNT(*) FROM tokenlogin_admin WHERE user_id = :id";

        $stmtUser = $conn->prepare($queryUser);
        $stmtUser->bindParam(':id', $id);
        $stmtUser->execute();
        $countUser = $stmtUser->fetchColumn();

        $stmtAdmin = $conn->prepare($queryAdmin);
        $stmtAdmin->bindParam(':id', $id);
        $stmtAdmin->execute();
        $countAdmin = $stmtAdmin->fetchColumn();

        return $countUser > 0 || $countAdmin > 0;
    }



}
