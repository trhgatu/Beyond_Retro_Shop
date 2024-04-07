<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
require_once '../class/category.php';

$category = new Category($conn);
$category->deleteCategory();
