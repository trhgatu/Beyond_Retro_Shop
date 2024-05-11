<?php
session_start();
include "../user/config.php";
require_once ("../db_function/connect.php");
require_once ("../db_function/functions.php");
require_once ("../db_function/database.php");
require_once ("../db_function/session.php");
require_once '../class/product.php';
require_once '../class/category.php';
$data = [
    'pageTitle' => 'Shop',
];
//Kiểm tra trạng thái đăng nhập
if (!isUserLogin()) {
    redirect('../user/?module=authen&action=login');
}
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Male_Fashion Template">
    <meta name="keywords" content="Male_Fashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

<body>
    <?php
    layout('header', $data);
    layout('search', $data);
    ?>
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Shop</h4>
                        <div class="breadcrumb__links">
                            <a href="./index.html">Home</a>
                            <span>Shop</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop Section Begin -->
    <section class="shop spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="shop__sidebar">
                        <div class="shop__sidebar__search">
                            <form method="GET">
                                <input type="text" placeholder="Tìm kiếm..." name="query">
                                <button type="submit"><span class="icon_search"></span></button>
                            </form>
                        </div>
                        <?php
                        ?>
                        <div class="shop__sidebar__accordion">
                            <div class="accordion" id="accordionExample">
                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseOne">Danh mục</a>
                                    </div>
                                    <div id="collapseOne" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="shop__sidebar__categories">
                                                <ul class="nice-scroll">
                                                    <?php
                                                    $categories = new Category($conn);
                                                    $listCategories = $categories->listCategories();
                                                    if (!empty($listCategories)):
                                                        foreach ($listCategories as $item):
                                                            ?>
                                                            <li><a href="shop.php?category_id=<?php echo $item['id']; ?>">
                                                                    <?php echo $item['name'] ?>
                                                                </a></li>
                                                            <?php
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseThree">Giá sản phẩm</a>
                                    </div>
                                    <div id="collapseThree" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="shop__sidebar__price">
                                                <ul>
                                                    <li><a href="shop.php?price-range=0-100000">0₫ - 100.000₫</a></li>
                                                    <li><a href="shop.php?price-range=100000-500000">100.000₫ -
                                                            500.000₫</a></li>
                                                    <li><a href="shop.php?price-range=500000-1000000">500.000₫ -
                                                            1.000.000₫</a>
                                                    </li>
                                                    <li><a href="shop.php?price-range=1000000-1500000">1.000.000₫ -
                                                            1.500.000₫</a>
                                                    </li>
                                                    <li><a href="shop.php?price-range=1500000+">1.500.000₫+</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseSix">Tags</a>
                                    </div>
                                    <div id="collapseSix" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="shop__sidebar__tags">
                                                <a href="#">Product</a>
                                                <a href="#">Bags</a>
                                                <a href="#">Shoes</a>
                                                <a href="#">Fashio</a>
                                                <a href="#">Clothing</a>
                                                <a href="#">Hats</a>
                                                <a href="#">Accessories</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="shop__product__option">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="shop__product__option__left">
                                    <h4>Tất cả sản phẩm</h4>
                                    <hr style="width:207%">
                                    </hr>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="shop__product__option__right">
                                    <p>Sort by Price:</p>
                                    <select>
                                        <option value="">Low To High</option>
                                        <option value="">$0 - $55</option>
                                        <option value="">$55 - $100</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        $product = new Product($conn);
                        if (isset($_GET['category_id']) && !empty($_GET['category_id'])) {
                            $category_id = $_GET['category_id'];
                            $searchResult = $product->filterProductByCategory($category_id);
                        } elseif (isset($_GET['price-range']) && !empty($_GET['price-range'])) {
                            $price_range = $_GET['price-range'];
                            $searchResult = $product->filterProductByPrice($price_range);
                        } elseif (isset($_GET['query']) && !empty($_GET['query'])) {
                            $query = $_GET['query'];
                            $searchResult = $product->searchProduct($query);
                        } else {
                            $searchResult = $product->listProductPagination();
                        }
                        if (!empty($searchResult)) {
                            foreach ($searchResult as $item) {
                                ?>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="product__item">
                                        <div class="product__item__pic set-bg">
                                            <img class="product__item__pic set-bg"
                                                src="../images/products/thumbnail/<?php echo $item['thumbnail'] ?>">
                                        </div>
                                        <div class="product__item__title">
                                            <a
                                                href="http://localhost/Beyond_Retro/include/shop-details.php?id=<?php echo $item['id']; ?>">
                                                <?php echo $item['name'] ?>
                                            </a>
                                        </div>
                                        <div class="product__item__text">
                                            <h5 style="font-weight: 700">
                                                <?php echo number_format($item['price'], 0, ',', '.') ?>₫
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            ?>
                            <div class="alert alert-danger text-center">Không có sản phẩm nào</div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?php
                            $items_per_page = 6;
                            $sql_total = "SELECT COUNT(*) AS total FROM product";
                            $stmt_total = $conn->query($sql_total);
                            $total = $stmt_total->fetch(PDO::FETCH_ASSOC)['total'];
                            $total_pages = ceil($total / $items_per_page);
                            ?>
                            <div class="product__pagination">
                                <?php
                                for ($i = 1; $i <= $total_pages; $i++) {
                                    $active_class = isset($_GET['page']) && $_GET['page'] == $i ? 'active' : '';
                                    ?>
                                    <a href="?page=<?php echo $i ?>"
                                        class="<?php echo $active_class ?>"><?php echo $i ?></a>
                                    <?php
                                }
                                ?>
                            </div>

                        </div>
                    </div>

                </div>
    </section>
    <!-- Shop Section End -->

    <!-- Footer Section Begin -->

    <?php
    layout('footer', $data);
    layout('search', $data);
    ?>


    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/jquery.nicescroll.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>