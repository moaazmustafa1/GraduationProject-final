<?php
if (isset($_GET["product_id"])) {
    require "server/connection.php";
    require "server/AddToCart.php";

    $product_id = $_GET["product_id"];
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ? ;");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result();

    while ($row = $product->fetch_assoc()) {
        $guess = $row["product_category"];
    }
    $stmt2 = $conn->prepare(
        "SELECT * FROM products WHERE product_category = ? ;"
    );
    $stmt2->bind_param("s", $guess);
    $stmt2->execute();
    $related_product = $stmt2->get_result();

    mysqli_data_seek($product, 0); //This is done because after the loop finishes the cursor will be at the last row, hence we make it start again from the beginning. See line 36
} else {
    header("location: shop.php");
    exit();
} ?>

<?php require "layouts/header.php"; ?>

<!-- Single Product -->
<section class="container single-product my-5 pt-5">
  <div class="row mt-5">

    <?php while (
        $row = $product->fetch_assoc()
    ): ?>    <!--This is why we reseted the cursor at line 23-->

      <div class="col-lg-5 col-md-6 col-sm-12 mx-auto">
        <img
          id="mainImg"
          class="img-fluid w-100 pb-1"
          src="assets/imgs/<?php echo $row["product_image"]; ?>" />
        <div class="small-img-group">
          <div class="small-img-col" style="text-align: center; vertical-align: middle;">
            <img src="assets/imgs/<?php echo $row[
                "product_image"
            ]; ?>" height="65px" class="small-img" />
          </div>

          <div class="small-img-col" style="text-align: center; vertical-align: middle;">
            <img src="assets/imgs/<?php echo $row[
                "product_image2"
            ]; ?>" height="65px" class="small-img" />
          </div>

          <div class="small-img-col" style="text-align: center; vertical-align: middle;">
            <img src="assets/imgs/<?php echo $row[
                "product_image3"
            ]; ?>" height="65px" class="small-img" />
          </div>

          <div class="small-img-col" style="text-align: center; vertical-align: middle;">
            <img src="assets/imgs/<?php echo $row[
                "product_image4"
            ]; ?>" height="65px" class="small-img" />
          </div>
        </div>
      </div>


      <div class="col-lg-6 col-md-12 col-sm-12 pt-5 mx-auto">
        <h6><?php echo $row["product_category"]; ?></h6>
        <h3 class="py-4"><?php echo $row["product_name"]; ?></h3>
        <h2><?php echo $row["product_price"]; ?> EGP</h2>

        <form method="post" action="">
          <input type="hidden" name="product_id" value="<?php echo $row[
              "product_id"
          ]; ?>">
          <input type="hidden" name="product_image" value="<?php echo $row[
              "product_image"
          ]; ?>">
          <input type="hidden" name="product_name" value="<?php echo $row[
              "product_name"
          ]; ?>">
          <input type="hidden" name="product_price" value="<?php echo $row[
              "product_price"
          ]; ?>">
          <input type="number" name="product_quantity" value="1" min="1" max="20" />
          <button class="buy" type="submit" name="add-to-cart">ADD TO CART</button>
        </form>

        <h4 class="mt-5 mb-5">Product description:</h4>
        <span><?php echo $row["product_description"]; ?></span>
      </div>


    <?php endwhile; ?>

  </div>
</section>

<!-- Related Products -->
<section id="related-products" class="my-5">
  <div class="container text-center mt-5 py-5">
    <h3>Related Products</h3>
    <hr />
  </div>
  <div class="row mx-auto container-fluid">

    <?php while ($row = $related_product->fetch_assoc()): ?>


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

<script>
  var mainImg = document.getElementById("mainImg");
  var mainImg2 = document.getElementById("mainImg");
  var smallImg = document.getElementsByClassName("small-img");

  for (let i = 0; i < 4; i++) {
    smallImg[i].onclick = function() {
      mainImg.src = smallImg[i].src;
    };
  }
</script>

<?php require "layouts/footer.php"; ?>
