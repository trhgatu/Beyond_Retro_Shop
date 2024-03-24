<?php
class Authen
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    function active()
    {
        $token = filter()['token'];
        if (!empty ($token)) {
            $tokenQuery = oneRaw("SELECT id FROM user WHERE activeToken = '$token'");
            if (!empty ($tokenQuery)) {
                $userId = $tokenQuery['id'];
                $dataUpdate = [
                    'status' => 1,
                    'activeToken' => null
                ];

                $updateStatus = update('user', $dataUpdate, "id = $userId");

                if ($updateStatus) {
                    return true; // Trả về true nếu cập nhật thành công
                } else {
                    return false; // Trả về false nếu có lỗi khi cập nhật
                }
            } else {
                return false; // Trả về false nếu không tìm thấy token trong cơ sở dữ liệu
            }
        } else {
            return false; // Trả về false nếu không có token được cung cấp
        }
    }
}



