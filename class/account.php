<?php
class Account
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function confirmPassword()
    {

        $filterAll = filter();
        if (!empty(trim($filterAll['password']))) {
            $password = $filterAll['password'];

            // Lấy userId từ session
            $token = getSession('tokenlogin');
            $query = "SELECT user_id FROM tokenlogin WHERE token = :token";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            $userQuery = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($userQuery)) {
                $userId = $userQuery['user_id'];

                // Truy vấn cơ sở dữ liệu để lấy mật khẩu của người dùng
                $query_user = "SELECT password FROM user WHERE id = :userId";
                $stmt_user = $this->conn->prepare($query_user);
                $stmt_user->bindParam(':userId', $userId);
                $stmt_user->execute();
                $userResult = $stmt_user->fetch(PDO::FETCH_ASSOC);

                if (!empty($userResult)) {
                    $passwordHash = $userResult['password'];
                    if (password_verify($password, $passwordHash)) {
                        setFlashData('msg', 'Xác nhận thành công');
                        setFlashData('msg_type', 'success');
                        redirect('?module=account&action=changepassword');
                    } else {
                        // Mật khẩu không chính xác
                        setFlashData('msg', 'Mật khẩu không chính xác, vui lòng thử lại.');
                        setFlashData('msg_type', 'danger');
                    }
                } else {
                    // Không tìm thấy thông tin người dùng
                    setFlashData('msg', 'Không tìm thấy thông tin người dùng.');
                    setFlashData('msg_type', 'danger');
                }
            }
        } else {
            // Không nhập mật khẩu
            setFlashData('msg', 'Vui lòng nhập mật khẩu hiện tại.');
            setFlashData('msg_type', 'danger');
        }

    }
    public function changePassword()
    {
        $token = getSession('tokenlogin');
        if (!empty($token)) {
            // Truy vấn cơ sở dữ liệu để lấy ID người dùng từ token
            $query = "SELECT user_id FROM tokenlogin WHERE token = :token";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            $userQuery = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($userQuery['user_id'])) {
                $userId = $userQuery['user_id'];
                $filterAll = filter();
                // Mảng $error chứa các lỗi
                $error = [];
                // Validate password: bắt buộc phải nhập, ít nhất 8 ký tự
                if (empty($filterAll['password'])) {
                    $error['password']['required'] = 'Mật khẩu bắt buộc phải nhập.';
                } else {
                    if (strlen($filterAll['password']) < 8) {
                        $error['password']['min'] = 'Mật khẩu phải nhiều hơn 8 ký tự';
                    }
                }
                // Validate password confirm: bắt buộc phải nhập, giống password
                if (empty($filterAll['password_confirm'])) {
                    $error['password_confirm']['required'] = 'Bạn phải nhập lại mật khẩu.';
                } else {
                    if ($filterAll['password'] != $filterAll['password_confirm']) {
                        $error['password_confirm']['match'] = 'Mật khẩu nhập lại không đúng.';
                    }
                }
                if (empty($error)) {
                    $passwordHash = password_hash($filterAll['password'], PASSWORD_DEFAULT);
                    $dataUpdate = [
                        'password' => $passwordHash,
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    $updateStatus = update('user', $dataUpdate, "id = '$userId'");
                    if ($updateStatus) {
                        setFlashData('msg', 'Thay đổi mật khẩu thành công.');
                        setFlashData('msg_type', 'success');
                        redirect('?module=account&action=changepassword');
                    } else {
                        setFlashData('msg', 'Lỗi hệ thống, vui lòng thử lại sau.');
                        setFlashData('msg_type', 'danger');
                    }
                } else {
                    setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu');
                    setFlashData('msg_type', 'danger');
                    setFlashData('error', $error);
                    redirect('?module=account&action=changepassword');
                }
            } else {
                setFlashData('msg', 'Không tìm thấy thông tin người dùng.');
                setFlashData('msg_type', 'danger');
                redirect('?module=authen&action=login');
            }
        }
    }
    public function updateInfo($filterAll, $avatarPath)
    {
        $token = getSession('tokenlogin');
        if (!empty($token)) {
            $query = "SELECT user_id FROM tokenlogin WHERE token = :token";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            $userQuery = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($userQuery['user_id'])) {
                $userId = $userQuery['user_id'];
                $filterAll = filter();

                $dataUpdate = [
                    'fullname' => $filterAll['fullname'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                if ($avatarPath) {
                    $dataUpdate['avatar'] = basename($avatarPath);
                }

                $condition = "id = $userId";
                $UpdateStatus = update('user', $dataUpdate, $condition);

                if ($UpdateStatus) {
                    setFlashData('msg', 'Cập nhật thông tin thành công.');
                    setFlashData('msg_type', 'success');
                    redirect('?module=account&action=profile');
                } else {
                    setFlashData('msg', 'Cập nhật thông tin thất bại, vui lòng thử lại.');
                    setFlashData('msg_type', 'danger');
                }
                redirect('?module=account&action=profile');
            }
        }
    }
    public function updateInfoAdmin($filterAll, $avatarAdminPath)
    {
        $token = getSession('tokenlogin_admin');
        if (!empty($token)) {
            $query = "SELECT user_id FROM tokenlogin_admin WHERE token = :token";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            $userQuery = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($userQuery['user_id'])) {
                $userId = $userQuery['user_id'];
                $filterAll = filter();
                $dataUpdate = [
                    'fullname' => $filterAll['fullname'],
                    'phone_number' =>  $filterAll['phone_number'],
                    'updated_at' => date('Y-m-d H:i:s'),

                ];
                if ($avatarAdminPath) {
                    $dataUpdate['avatar'] = basename($avatarAdminPath);
                }
                $condition = "id = $userId";
                $UpdateStatus = update('user', $dataUpdate, $condition);

                if ($UpdateStatus) {
                    setFlashData('msg', 'Cập nhật thông tin thành công.');
                    setFlashData('msg_type', 'success');
                    redirect('?module=account&action=profile');
                } else {
                    setFlashData('msg', 'Cập nhật thông tin thất bại, vui lòng thử lại.');
                    setFlashData('msg_type', 'danger');
                }
                redirect('?module=account&action=profile');
            }
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
            $dest = '../images/avatar' . $fname . "-$i." . $extension;
            $i++;
        }
        return $dest;
    }
}



