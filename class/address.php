<?php

class Address
{
    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function addAddress()
    {
        $filterAll = filter();
        $user_id = getSession('user_id');
        if (!$user_id) {
            setFlashData('msg', 'Vui lòng đăng nhập để thêm địa chỉ.');
            setFlashData('msg_type', 'danger');
            redirect('?module=authen&action=login');
        }
        $dataInsert = [
            'address' => $filterAll['address'],
            'district' => $filterAll['district'],
            'city' => $filterAll['city'],
            'country' => $filterAll['country'],
            'user_id' => $user_id,
            'is_default' => 1,
        ];

        $insertStatus = insert('addresses', $dataInsert);
        if ($insertStatus) {
            setFlashData('msg', 'Thêm địa chỉ mới thành công.');
            setFlashData('msg_type', 'success');
            redirect('?module=address&action=list');
        } else {
            setFlashData('msg', 'Thêm địa chỉ thất bại, vui lòng thử lại.');
            setFlashData('msg_type', 'danger');
            redirect('?module=address&action=add');
        }
    }
    public function deleteAddress()
    {
        $filterAll = filter();
        if (!empty($filterAll['id'])) {
            $addressId = $filterAll['id'];
            $addressDetail = getRaw("SELECT * FROM addresses WHERE id = $addressId");
            if ($addressDetail > 0) {
                $deleteAddress = delete('addresses', "id = $addressId");
                if ($deleteAddress) {
                    setFlashData('msg', 'Xóa địa chỉ thành công.');
                    setFlashData('msg_type', 'success');
                } else {
                    setFlashData('msg', 'Không thể xóa địa chỉ, vui lòng thử lại.');
                    setFlashData('msg_type', 'danger');
                }

            } else {
                setFlashData('msg', 'Địa chỉ không tồn tại trong hệ thống.');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Liên kết không tồn tại.');
            setFlashData('msg_type', 'danger');
        }
        redirect('../user/?module=address&action=list');
    }
    public function setDefaultAddress()
    {
        $filterAll = filter();
        $addressId = $filterAll['id'];
        $user_id = getSession('user_id');
        if (!$user_id) {
            setFlashData('msg', 'Vui lòng đăng nhập để thực hiện thao tác này.');
            setFlashData('msg_type', 'danger');
            redirect('?module=authen&action=login');
        }
        $sql = "UPDATE addresses SET is_default = 0 WHERE user_id = $user_id";
        $this->conn->query($sql);


        $sql = "UPDATE addresses SET is_default = 1 WHERE id = $addressId";
        $this->conn->query($sql);

        setFlashData('msg', 'Đặt địa chỉ mặc định thành công.');
        setFlashData('msg_type', 'success');
        redirect('?module=address&action=list');
    }

    public function getAddresses($userId)
    {

        $sql = "SELECT * FROM addresses WHERE user_id = $userId";
        $result = $this->conn->query($sql);

        $addresses = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $addresses[] = $row;
            }
        }
        return $addresses;
    }
}
