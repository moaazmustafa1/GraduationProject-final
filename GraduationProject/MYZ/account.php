<?php

session_start();

include "server/connection.php";

if (isset($_SESSION["logged_in2"])):
    header("location: admin/index.php");
    exit();
elseif (!isset($_SESSION["logged_in"])):
    header("location: login.php");
    exit();
elseif (isset($_GET["logout"])):
    unset($_SESSION["logged_in"]);
    unset($_SESSION["user_name"]);
    unset($_SESSION["user_email"]);
    unset($_SESSION["user_id"]);
    session_regenerate_id(true);

    header("location: index.php");
    exit();
elseif (isset($_POST["change_password"])):
    $password = $_POST["password"];
    $confirm_password = $_POST["confirmPassword"];

    if (empty($password)):
        $error = "Please fill in the password field.";
    elseif (empty($confirm_password)):
        $error = "Please fill in the confirm password field.";
    else:
        if (strlen($password) < 9):
            $error = "Password must be at least 9 characters.";
        elseif ($confirm_password !== $password):
            $error = "Passwords do not match.";
        else:
            $user_email = $_SESSION["user_email"];
            $password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "UPDATE users SET user_password = ? WHERE BINARY user_email = ? ;"
            );

            $stmt->bind_param("ss", $password, $user_email);

            if ($stmt->execute()):
                $message2 = "Password changed successfully.";
            else:
                $error = "Unexpected error: Could not change password.";
            endif;
        endif;
    endif;
endif;

// Get order(s) from database
$user_id = $_SESSION["user_id"];

$stmt1 = $conn->prepare("SELECT * FROM orders
  WHERE user_id = ? ; ");

$stmt1->bind_param("i", $user_id);
$stmt1->execute();

$orders = $stmt1->get_result();
?>





<?php require "layouts/header.php"; ?>

<!-- Account -->
<section class="my-5 py-5">
  <p class="text-center mx-auto pt-5" style="color: green"><?php if (
      isset($_GET["message"])
  ) {
      echo filter_var($_GET["message"], FILTER_SANITIZE_STRING); //Used filter_var to protect website against XSS
  } ?></p>
  <div class="row container mx-auto">
    <div class="text-center pt-5 col-lg-6 col-md-12 col-sm-12">
      <h3 class="font-weight-bold">Account info</h3>
      <hr class="mx-auto" />
      <br />
      <div class="account-info">
        <p>Name: <span> <?php echo $_SESSION["user_name"]; ?></span></p>
        <p>Email: <span><?php echo $_SESSION["user_email"]; ?></span></p>
        <p><a href="#orders" class="btn" id="orders-btn">View Orders</a></p>
        <p><a href="account.php?logout=1" class="btn" id="logout-btn">Logout</a></p>
      </div>
    </div>
    <div class="col-lg-6 col-md-12 col-sm-12">
      <form id="account-form" method="post" action="account.php">
        <p style="color:<?php if (isset($error)) {
            echo "red";
        } elseif (isset($message2)) {
            echo "green";
        } ?>"><?php if (isset($error)) {
    echo $error;
} elseif (isset($message2)) {
    $m = $message2;
    echo $m;
} ?></p>
        <h3>Change Password</h3>
        <hr class="mx-auto" />
        <br />
        <div class="form-group">
          <label>Password</label>
          <input
            type="password"
            class="form-control"
            id="account-password"
            name="password"
            placeholder="Password"
            required />
        </div>
        <div class="form-group">
          <label>Confirm Password</label>
          <input
            type="password"
            class="form-control"
            id="account-password-confirm"
            name="confirmPassword"
            placeholder="Confirm Password"
            required />
        </div>
        <div class="form-group">
          <input
            type="submit"
            class="btn"
            id="change-pass-btn"
            name="change_password"
            value="Change password" />
        </div>
      </form>
    </div>
  </div>
</section>

<!-- Orders -->
<section id="orders" class="container orders text-center my-5 py-3">
  <div class="container mt-2">
    <h2 class="font-weight-bolde text-center">Orders</h2>
    <hr class="mx-auto" />
  </div>
  <table class="mt-5 pt-5">
    <tr>
      <th>Order #</th>
      <th>Cost</th>
      <th>Status</th>
      <th>Date</th>
      <th></th>
    </tr>

    <?php while ($row = $orders->fetch_assoc()): ?>

      <tr>
        <td>
          <span class="mt-3"><?php echo $row["order_id"]; ?></span>
        </td>
        <td>
          <span><?php echo $row["order_cost"]; ?> EGP</span>
        </td>
        <td>
          <span><?php echo $row["order_status"]; ?></span>
        </td>
        <td>
          <span><?php echo date(
              "d/m/Y",
              strtotime($row["order_date"])
          ); ?></span>
        </td>
        <td>
          <form action="order_details.php" method="post">
            <input type="hidden" name="order-id" value="<?php echo $row[
                "order_id"
            ]; ?>">
            <input type="hidden" name="order-cost" value="<?php echo $row[
                "order_cost"
            ]; ?>">
            <input type="submit" class="btn3" name="order-details" value="Order Details">
          </form>
        </td>
      </tr>

    <?php endwhile; ?>

  </table>
</section>

<?php require "layouts/footer.php"; ?>
