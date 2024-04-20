<?php
include "../user/config.php";
require_once ("../db_function/connect.php");
require_once ("../db_function/functions.php");
require_once ("../db_function/database.php");
require_once ("../db_function/session.php");
require_once '../class/product.php';
require_once '../class/category.php';
session_start();
$data = [
    'pageTitle' => 'Shop',
];
//Kiểm tra trạng thái đăng nhập
if (!isUserLogin()) {
    redirect('../user/?module=authen&action=login');
}
if (isset($_GET['query'])) {
    $search_query = '%' . $_GET['query'] . '%';
    $sql = "SELECT * FROM product WHERE name LIKE :search_query";

    // Thực thi truy vấn
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':search_query', $search_query, PDO::PARAM_STR);
    $stmt->execute();

    // Lấy kết quả
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Hiển thị kết quả tìm kiếm
    if (count($results) > 0) {
        echo "<ul>";
        foreach ($results as $row) {
            echo "<li>{$row['name']}</li>";
        }
        echo "</ul>";
    } else {
        echo "Không tìm thấy kết quả phù hợp";
    }
}
