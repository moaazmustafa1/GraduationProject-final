<?php

session_start();
if (isset($_SESSION["logged_in2"])):
    header("location: admin/index.php");
    exit();
elseif (isset($_POST["checkout"]) || isset($_GET["error"])):
    if (!isset($_SESSION["logged_in"])):
        header("location: login.php?error=Please login first.");
        exit();
    elseif (empty($_SESSION["cart"])):
        header("location: cart.php?error=Your cart is empty.");
        exit();
    endif;
else:
    header("location: cart.php");
    exit();
endif;
?>




<?php require "layouts/header.php"; ?>

<!-- Checkout -->

<section class="my-5 py-5">
  <div class="container text-center mt-3 pt-5">
    <h2 class="form-weight-bold">Check Out</h2>
    <hr class="mx-auto" />
    <?php if (isset($_GET["error"])):
        echo "<p class=\"mt-3 text-center pt-3\" style=\"color: red;\">";
        echo $_GET["error"];
        echo "</p>";
    endif; ?>
  </div>
  <div class="mx-auto container">
    <form id="checkout-form" action="server/place_order.php" method="post">

      <div class="form-group checkout-small-element">
        <label>Name</label>
        <input
          type="text"
          class="form-control"
          id="checkout-name"
          name="name"
          placeholder="Name"
          <?php if (isset($_SESSION["user_name"])) {
              echo 'value="';
              echo $_SESSION["user_name"];
              echo '"';
          } ?>
          required />
      </div>
      <div class="form-group checkout-small-element">
        <label>Email</label>
        <input
          type="text"
          class="form-control"
          id="checkout-email"
          name="email"
          placeholder="Email"
          <?php if (isset($_SESSION["user_email"])) {
              echo 'value="';
              echo $_SESSION["user_email"];
              echo '"';
          } ?>
          required />
      </div>
      <div class="form-group checkout-small-element">
        <label>Phone</label>
        <input
          type="tel"
          class="form-control"
          id="checkout-phone"
          name="phone"
          placeholder="Phone"
          required />
      </div>
      <div class="form-group checkout-small-element">
        <label>City</label>
        <input
          type="text"
          class="form-control"
          id="checkout-city"
          name="city"
          placeholder="City"
          required />
      </div>
      <div class="form-group checkout-large-element">
        <label>Address</label>
        <input
          type="text"
          class="form-control"
          id="checkout-adress"
          name="address"
          placeholder="Address"
          required />
      </div>
      <div class="form-group checkout-btn-container">
        <p>Total amount: <?php echo $_SESSION["total"]; ?> EGP</p>
        <input
          type="submit"
          class="btn"
          id="checkout-btn"
          name="place_order"
          value="Place Order" />
      </div>
    </form>
  </div>
</section>

<?php require "layouts/footer.php"; ?>
