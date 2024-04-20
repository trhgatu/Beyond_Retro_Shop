<?php
class Cart
{
    private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function addToCart()
    {
        if (isset($_POST['addtocart']) && isset($_POST['product_id'])) {
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'];

            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = array();
            }
            $_SESSION['cart'][$productId] = isset($_SESSION['cart'][$productId]) ? $_SESSION['cart'][$productId] + $quantity : $quantity;

            $this->updateCartCount();
        }
    }
    public function removeFromCart()
    {
        if (isset($_GET['action']) && $_GET['action'] === 'removeproduct' && isset($_GET['id'])) {
            $productId = $_GET['id'];
            if (isset($_SESSION['cart'][$productId])) {
                unset($_SESSION['cart'][$productId]);
                $this->updateCartCount();
            }

        }
    }
    public function updateCart(){
        if (isset($_POST['update-cart'])) {
            foreach ($_POST['quantity'] as $productId => $quantity) {
                if ($quantity > 0) {
                    $_SESSION['cart'][$productId] = $quantity;
                } else {
                    unset($_SESSION['cart'][$productId]);
                }
            }
            $this->updateCartCount();
        }
    }
    private function updateCartCount()
    {
        $totalQuantity = array_sum($_SESSION['cart']);
        $_SESSION['cart_count'] = $totalQuantity;
    }

}