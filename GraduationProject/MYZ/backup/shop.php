<?php

include "server/connection.php";

if (isset($_POST["search"])) {
    // We can use POST for more security

    $category = filter_var($_POST["category"], FILTER_SANITIZE_STRING);
    $min_price = filter_var($_POST["min_price"], FILTER_SANITIZE_NUMBER_INT);
    $max_price = filter_var($_POST["max_price"], FILTER_SANITIZE_NUMBER_INT);

    if ($category !== "none"):
        $stmt2 = $conn->prepare(
            "SELECT product_id, product_image, product_name, product_price FROM products WHERE  product_category = ? AND product_price >= ? AND product_price <= ? ;"
        );
        $stmt2->bind_param("sii", $category, $min_price, $max_price);
    else:
        $stmt2 = $conn->prepare(
            "SELECT product_id, product_image, product_name, product_price FROM products WHERE product_price >= ? AND product_price <= ? ;"
        );
        $stmt2->bind_param("ii", $min_price, $max_price);
    endif;

    if ($stmt2->execute()):
        $products = $stmt2->get_result();
    else:
        echo '<script>alert("There was a problem during filtering products");</script>';
        header("location: shop.php");
        exit();
    endif;
} else {
    // Find how many products are available
    $stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM products;");
    $stmt1->execute();
    $stmt1->bind_result($total_records);
    $stmt1->store_result();
    $stmt1->fetch();

    // Number of products per page
    $total_records_per_page = 8;

    $number_of_pages = ceil($total_records / $total_records_per_page);

    // Get page number
    if (isset($_GET["page_no"])):
        $page_no = filter_var($_GET["page_no"], FILTER_SANITIZE_NUMBER_INT);
        $page_no = (int) $page_no;
    endif;

    if (!isset($page_no)):
        $page_no = 1;
        $page_no = (int) $page_no;
    endif;

    $offset = ($page_no - 1) * $total_records_per_page;

    $stmt2 = $conn->prepare("SELECT * FROM products LIMIT ?, ? ;");
    $stmt2->bind_param("ii", $offset, $total_records_per_page);
    $stmt2->execute();
    $products = $stmt2->get_result();
}
?>








<?php require "layouts/header.php"; ?>

<!-- Filter -->
<section id="search">
  <div class="container mt-5 py-5">
    <p style="font-weight: bold;">Filter Products</p>
    <hr>
  </div>

  <form action="shop.php" method="post">
    <div class="row mx-auto container">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <p>Category</p>
        <div class="form_check">
          <input class="form-check-input" type="radio" name="category" id="category_one" value="none" <?php if (
              isset($category)
          ) {
              if ($category === "none") {
                  echo "checked";
              }
          } else {
              echo "checked";
          } ?>>
          <label class="form-check-label" for="flexRadioDefault1">None</label>
        </div>

        <div class="form_check">
          <input class="form-check-input" type="radio" name="category" id="category_two" value="Sofa" <?php if (
              isset($category) &&
              $category === "Sofa"
          ) {
              echo "checked";
          } ?>>
          <label class="form-check-label" for="flexRadioDefault2">Sofa</label>
        </div>

        <div class="form_check">
          <input class="form-check-input" type="radio" name="category" id="category_two" value="Bed" <?php if (
              isset($category) &&
              $category === "Bed"
          ) {
              echo "checked";
          } ?>>
          <label class="form-check-label" for="flexRadioDefault2">Bed</label>
        </div>

        <div class="form_check">
          <input class="form-check-input" type="radio" name="category" id="category_two" value="Armchair" <?php if (
              isset($category) &&
              $category === "Armchair"
          ) {
              echo "checked";
          } ?>>
          <label class="form-check-label" for="flexRadioDefault2">Armchair</label>
        </div>

        <div class="form_check">
          <input class="form-check-input" type="radio" name="category" id="category_two" value="Table" <?php if (
              isset($category) &&
              $category === "Table"
          ) {
              echo "checked";
          } ?>>
          <label class="form-check-label" for="flexRadioDefault2">Table</label>
        </div>
      </div>
    </div>

    <div class=" priceRange2 row mx-auto container mt-5">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <p>Price Range: </p>
        <input type="number" class="priceRange" min="1" max="10000" name="min_price" placeholder="From" value="<?php if (
            isset($min_price)
        ) {
            echo filter_var($min_price, FILTER_SANITIZE_NUMBER_INT);
        } else {
            echo 1;
        } ?>">
        <input type="number" class="priceRange" min="1" max="10000" name="max_price" placeholder="To" value="<?php if (
            isset($max_price)
        ) {
            echo filter_var($max_price, FILTER_SANITIZE_NUMBER_INT);
        } else {
            echo 10000;
        } ?>">
      </div>
    </div>

    <div class="form-group my-3 mx-3">
      <input type="submit" name="search" value="Filter" class="btn btn-primary">
    </div>
  </form>
</section>

<!-- Products List -->
<section id="shop" class="my-5 py-5">
  <div class="container text-center mt-5 py-5">
    <h3>Products</h3>
    <hr />
  </div>
  <div class="row mx-auto container-fluid">

    <?php while ($row = $products->fetch_assoc()): ?>

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
        ]; ?>"><button class="buy btn-primary">Buy now</button></a>
      </div>

    <?php endwhile; ?>

    <?php if (!isset($_GET["search"])): ?>
    <div class="center my-3">
        <div class="pagination">
            <a href="<?php if ($page_no > 1) {
                $previous_page = $page_no - 1;
                echo "shop.php?page_no=" . $previous_page;
            } else {
                echo "#";
            } ?>">&laquo;</a>

            <?php for ($i = 1; $i <= $number_of_pages; $i++):
                echo "<a href=\"shop.php?page_no=" . $i . '"';

                if ($i === $page_no):
                    echo ' class="active"';
                endif;

                echo ">" . $i . "</a>";
            endfor; ?>

            <a href="<?php if ($page_no < $number_of_pages) {
                $next_page = $page_no + 1;
                echo "shop.php?page_no=" . $next_page;
            } else {
                echo "#";
            } ?>">&raquo;</a>
        </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php require "layouts/footer.php"; ?>
