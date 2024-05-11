<?php
class Order
{
    private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function listOrder()
    {
        $orders = array();
        $query = "SELECT * FROM orders";
        $result = $this->conn->query($query);
        if ($result && $result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $orders[] = $row;
            }
        }
        return $orders;
    }
    public function placeOrder()
    {
        $user_id = getSession('user_id');
        $filterAll = filter();
        $status = 0;
        if (isset($filterAll['status']) && $filterAll['status'] === '1') {
            $status = 1;
        }
        $product_ids = array_keys($_SESSION['cart']);
        $quantities = array_values($_SESSION['cart']);
        $dataInsert = [
            'fullname' => $filterAll['fullname'],
            'user_id' => $user_id,
            'email' => $filterAll['email'],
            'phone_number' => $filterAll['phone_number'],
            'address' => $filterAll['address'],
            'district' => $filterAll['district'],
            'city' => $filterAll['city'],
            'country' => $filterAll['country'],
            'order_date' => date('Y-m-d H:i:s'),
            'note' => $filterAll['note'],
            'status' => $status,
            'total_money' => $filterAll['totalPrice'],
        ];

        $insertStatus = insert('orders', $dataInsert);

        if ($insertStatus) {
            $order_id = $this->conn->lastInsertId();
            $success = true;

            for ($i = 0; $i < count($product_ids); $i++) {
                $product = new Product($this->conn);
                $productDetail = $product->getByProductId($product_ids[$i]);
                $price = $productDetail[0]['price'];

                $dataOrderDetail = [
                    'order_id' => $order_id,
                    'product_id' => $product_ids[$i],
                    'price' => $price,
                    'num' => $quantities[$i],
                    'total_money' => $price * $quantities[$i],
                ];
                $insertOrderDetail = insert('order_details', $dataOrderDetail);
                if (!$insertOrderDetail) {
                    $success = false;
                }
            }
            if ($success) {
                setFlashData('msg', 'Đặt hàng thành công');
                unset($_SESSION['cart']);
                unset($_SESSION['cart_count']);
                redirect('http://localhost/Beyond_Retro/include/thankyou.php');
            } else {
                setFlashData('msg', 'Đặt hàng thất bại.');
                setFlashData('msg_type', 'danger');
                redirect('http://localhost/Beyond_Retro/include/checkout.php');
            }
        } else {
            setFlashData('msg', 'Đặt hàng thất bại.');
            setFlashData('msg_type', 'danger');
        }
    }




}