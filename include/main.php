
<div class="slider">
  <div class="list">
    <div class="item">
      <img src="../img/hero/hero-banner-1.png" alt="">
    </div>
    <div class="item">
      <img src="../img/hero/hero-banner-4.jpg" alt="">
    </div>
    <div class="item">
      <img src="../img/hero/hero-banner-2.jpg" alt="">
    </div>
    <div class="item">
      <img src="../img/hero/hero-banner-3.jpg" alt="">
    </div>
    <div class="item">
      <img src="../img/hero/hero-banner-5.jpg" alt="">
    </div>
  </div>
  <div class="buttons">
    <button id="prev">
      < </button>
        <button id="next">></button>
  </div>
  <ul class="dots">
    <li class="active"></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
  </ul>
</div>


<section class="banner spad">
  <div class="container">
    <div class="row">

      <div class="col-lg-7 offset-lg-4">
        <div class="banner__item">
          <div class="banner__item__pic">
            <img src="../img/banner/banner-1.jpg" alt="">
          </div>
          <div class="banner__item__text">
            <h2>Clothing Collections 2030</h2>

          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="banner__item banner__item--middle">
          <div class="banner__item__pic">
            <img src="../img/banner/banner-2.jpg" alt="">
          </div>
          <div class="banner__item__text">
            <h2>Accessories</h2>

          </div>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="banner__item banner__item--last">
          <div class="banner__item__pic">
            <img src="../img/banner/banner-3.jpg" alt="">
          </div>
          <div class="banner__item__text">
            <h2>Shoes Spring 2030</h2>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="product spad">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <ul class="filter__controls">
          <li class="active" data-filter=".flag-products">Sản phẩm nổi bật</li>
        </ul>

      </div>
    </div>
    <div class="row product__filter">
      <?php
      $listFlagProducts = getRaw("SELECT * FROM product WHERE flag = 1");
      ?>
      <?php
      if ($listFlagProducts) {
        foreach ($listFlagProducts as $itemflag) {
          ?>
          <div class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix flag-products">
            <div class="product__item sale">
              <div class="product__item__pic set-bg"
                data-setbg="../images/products/thumbnail/<?php echo $itemflag['thumbnail'] ?>">
                <span class="label">Nổi bật</span>
              </div>
              <div class="product__item__text">
                <a href=""></a>
                <h6><?php echo $itemflag['name'] ?></h6>
                <h5><?php echo number_format($itemflag['price'], 0, ',', '.') ?>₫</h5>
              </div>
            </div>
          </div>
          <?php
        }
      }else{

      }
      ?>
  </div>
</section>

<section class="instagram spad">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <div class="instagram__pic">
          <div class="instagram__pic__item set-bg" data-setbg="../img/instagram/instagram-1.jpg"></div>
          <div class="instagram__pic__item set-bg" data-setbg="../img/instagram/instagram-2.jpg"></div>
          <div class="instagram__pic__item set-bg" data-setbg="../img/instagram/instagram-3.jpg"></div>
          <div class="instagram__pic__item set-bg" data-setbg="../img/instagram/instagram-4.jpg"></div>
          <div class="instagram__pic__item set-bg" data-setbg="../img/instagram/instagram-5.jpg"></div>
          <div class="instagram__pic__item set-bg" data-setbg="../img/instagram/instagram-6.jpg"></div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="instagram__text">
          <h2>Instagram</h2>

          <h3>#BeyondRetro</h3>
        </div>
      </div>
    </div>
  </div>
</section>

