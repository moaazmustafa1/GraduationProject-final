  <?php
  if (session_status() !== PHP_SESSION_ACTIVE):
      session_start();
  endif;

  if (isset($_SESSION["logged_in2"])) {
      header("location: admin/index.php");
      exit();
  }
  ?>


   <?php require "layouts/header.php"; ?>


  <!-- Home -->
  <section id="home">
    <div class="container">
      <h4>MID SEASON SALES!</h4>
      <h1>
        Winter Collection <br />
        UP to 40% OFF
      </h1>
      <a href='shop.php'><button>Explore</button></a>
    </div>
  </section>

  <!-- Brands -->
  <section id="brand" class="container mt-5 py-3">
    <div class="row">
      <img
        class="img-fluid col-lg-3 col-md-6 col-sm-12"
        src="assets/imgs/brand1.jpg" />
      <img
        class="img-fluid col-lg-3 col-md-6 col-sm-12"
        src="assets/imgs/brand2.jpg" />
      <img
        class="img-fluid col-lg-3 col-md-6 col-sm-12"
        src="assets/imgs/brand3.jpg" />
      <img
        class="img-fluid col-lg-3 col-md-6 col-sm-12"
        src="assets/imgs/brand4.jpg" />
    </div>
  </section>

  <!-- New Section -->
  <section id="new" class="w-100 mt-5 py-3">
    <div class="row p-0 m-0">
      <!-- First product -->
      <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
        <img class="img-fluid" src="assets/imgs/1.jpg" />
        <div class="details">
          <h2>Sofas</h2>
          <button onclick="window.location.href='shop.php?category=Sofa&min_price=1&max_price=10000&search=Filter';">
            Shop Sofas
          </button>
        </div>
      </div>

      <!-- Second product -->
      <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
        <img class="img-fluid" src="assets/imgs/5.jpg" />
        <div class="details">
          <h2>Beds</h2>
          <button onclick="window.location.href='shop.php?category=Bed&min_price=1&max_price=10000&search=Filter';">
            Shop Beds
          </button>
        </div>
      </div>

      <!-- Third product -->
      <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
        <img class="img-fluid" src="assets/imgs/12.jpg" />
        <div class="details">
          <h2>Tables</h2>
          <button onclick="window.location.href='shop.php?category=Table&min_price=1&max_price=10000&search=Filter';">
            Shop Tables
          </button>
        </div>
      </div>
    </div>
  </section>

  <!-- Featured products -->
  <section id="featured" class="my-5">
    <div class="container text-center mt-5 py-5">
      <h3>Featured products</h3>
      <hr />
    </div>
    <div class="row mx-auto container-fluid">

      <?php include "server/get_featured_products.php"; ?>

      <?php while ($row = $featured_products->fetch_assoc()): ?>

        <div
          class="product col-lg-3 col-md-6 col-sm-12">
          <a href="single_product.php?product_id=<?php echo $row[
              "product_id"
          ]; ?>"><img class="img-fluid" src="assets/imgs/<?php echo $row[
    "product_image"
]; ?>" /></a>
          <div class="star">
            <i class="fa-regular fa-star pt-3"></i>
            <i class="fa-regular fa-star pt-3"></i>
            <i class="fa-regular fa-star pt-3"></i>
            <i class="fa-regular fa-star pt-3"></i>
            <i class="fa-regular fa-star pt-3"></i>
          </div>
          <h5 class="p-name pt-2"><?php echo $row["product_name"]; ?></h5>
          <h4 class="p-price"><?php echo $row["product_price"]; ?> EGP</h4>
          <a href="single_product.php?product_id=<?php echo $row[
              "product_id"
          ]; ?>"><button class="buy">Buy now</button></a>
        </div>

      <?php endwhile; ?>
    </div>
  </section>

  <!-- Banner -->
  <!-- <section id="banner">
            <div class="container">
              <h4>MID SEASON'S SALE</h4>
              <h1>Winter Collection <br> UP to 40% OFF</h1>
              <button>Explore Collection</button>
            </div>
           </section> -->

  <!-- Sofas -->
  <section id="sofas" class="my-5">
    <div class="container text-center mt-5 py-5">
      <h3>Sofas</h3>
      <hr />
      <p class="pt-3">Check out modern sofas and couches.</p>
    </div>
    <div class="row mx-auto container-fluid">

      <?php include "server/get_sofas.php"; ?>

      <?php while ($row = $sofas_products->fetch_assoc()): ?>


        <div class="product col-lg-3 col-md-6 col-sm-12">
          <a href="single_product.php?product_id=<?php echo $row[
              "product_id"
          ]; ?>"><img class="img-fluid" src="assets/imgs/<?php echo $row[
    "product_image"
]; ?>" /></a>
          <div class="star">
            <i class="fa-regular fa-star pt-3"></i>
            <i class="fa-regular fa-star pt-3"></i>
            <i class="fa-regular fa-star pt-3"></i>
            <i class="fa-regular fa-star pt-3"></i>
            <i class="fa-regular fa-star pt-3"></i>
          </div>
          <h5 class="p-name pt-2"><?php echo $row["product_name"]; ?></h5>
          <h4 class="p-price"><?php echo $row["product_price"]; ?> EGP</h4>
          <a href="single_product.php?product_id=<?php echo $row[
              "product_id"
          ]; ?>"><button class="buy">Buy now</button></a>
        </div>

      <?php endwhile; ?>
    </div>
  </section>

  <!-- Beds -->
  <section id="beds" class="my-5">
    <div class="container text-center mt-5 py-5">
      <h3>Beds</h3>
      <hr />
      <p class="pt-3">Check out our bed collections.</p>
    </div>
    <div class="row mx-auto container-fluid">

      <?php include "server/get_beds.php"; ?>

      <?php while ($row = $beds->fetch_assoc()): ?>


        <div class="product col-lg-3 col-md-6 col-sm-12">
          <a href="single_product.php?product_id=<?php echo $row[
              "product_id"
          ]; ?>"><img class="img-fluid" src="assets/imgs/<?php echo $row[
    "product_image"
]; ?>" /></a>
          <div class="star">
            <i class="fa-regular fa-star pt-3"></i>
            <i class="fa-regular fa-star pt-3"></i>
            <i class="fa-regular fa-star pt-3"></i>
            <i class="fa-regular fa-star pt-3"></i>
            <i class="fa-regular fa-star pt-3"></i>
          </div>
          <h5 class="p-name pt-2"><?php echo $row["product_name"]; ?></h5>
          <h4 class="p-price"><?php echo $row["product_price"]; ?> EGP</h4>
          <a href="single_product.php?product_id=<?php echo $row[
              "product_id"
          ]; ?>"><button class="buy">Buy now</button></a>
        </div>


      <?php endwhile; ?>
    </div>
  </section>

  <?php require "layouts/footer.php"; ?>
