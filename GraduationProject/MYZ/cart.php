<?php

session_start();

if (isset($_SESSION["logged_in2"])) {
    header("location: admin/index.php");
    exit();
}

require "server/AddToCart.php";

if (isset($_POST["remove-product"])) {
    $product_id = $_POST["product_id"];
    unset($_SESSION["cart"][$product_id]);
    NoReload();
} elseif (isset($_POST["edit-quantity"])) {
    $product_id = $_POST["product_id"];
    $product_quantity = $_POST["product_quantity"];

    $_SESSION["cart"][$product_id]["product_quantity"] = $product_quantity;
    NoReload();
}
Total();
?>




<?php require "layouts/header.php"; ?>

<!-- cart -->
<section class="container cart my-5 py-5">
  <div class="container mt-5">
    <h2 class="font-weight-bolde text-center">Your Cart</h2>
    <hr />
    <?php if (isset($_GET["error"])):
        echo "<p class=\"mt-3 text-center pt-3\" style=\"color: red;\">";
        echo $_GET["error"];
        echo "</p>";
    endif; ?>
  </div>

  <table class="mt-5 pt-5">
    <tr>
      <th>Product</th>
      <th>Quantity</th>
      <th>Subtotal</th>
    </tr>

    <?php foreach ($_SESSION["cart"] as $key => $value): ?>
      <tr>
        <td>
          <div class="product-info">
            <img src="assets/imgs/<?php echo $value["product_image"]; ?>" />
            <div>
              <p><?php echo $value["product_name"]; ?></p>
              <small><?php echo $value[
                  "product_price"
              ]; ?> <span>EGP</span></small>
              <br />
              <br />
              <form action="cart.php" method="post">
                <!-- <input type="submit" name="remove-product" class="remove-btn" value="Remove"> -->
                <button type="submit" name="remove-product" value="Remove"><i class="fa-solid fa-trash"></i></button>
                <input type="hidden" name="product_id" value="<?php echo $value[
                    "product_id"
                ]; ?>">
              </form>
            </div>
          </div>
        </td>
        <td>
          <form action="cart.php" method="post">
            <input type="number" name="product_quantity" value="<?php echo $value[
                "product_quantity"
            ]; ?>" min="1" max="20" class="quantity.inp" />
            <!-- <input type="submit" name="edit-quantity" value="Edit" class="edit-btn"> -->
            <button type="submit" name="edit-quantity" value="Edit"><i class="fa-solid fa-pencil" class="btn"></i></button>
            <input type="hidden" name="product_id" value="<?php echo $value[
                "product_id"
            ]; ?>">
          </form>
        </td>
        <td>
          <span class="product-price"><?php echo $value["product_price"] *
              $value["product_quantity"]; ?> </span>
          <span>EGP</span>
        </td>
      </tr>

    <?php endforeach; ?>

  </table>

  <div class="cart-total">
    <table>
      <tr>
        <td>Total</td>
        <td><?php echo $_SESSION["total"]; ?> EGP</td>
      </tr>
    </table>
  </div>

  <div class="Checkout-container">

    <form action="checkout.php" method="post">
      <input type="submit" class="checkout-btn" name="checkout" value="Checkout">
    </form>
  </div>
</section>

<?php require "layouts/footer.php"; ?>
