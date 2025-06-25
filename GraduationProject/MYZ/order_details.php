<?php

session_start();

include "server/connection.php";

if (isset($_SESSION["logged_in2"])) {
    header("location: admin/index.php");
    exit();
} elseif (!isset($_SESSION["logged_in"])) {
    header("location: login.php");
    exit();
} elseif (isset($_POST["order-details"])) {
    $order_id = filter_var($_POST["order-id"], FILTER_SANITIZE_NUMBER_INT);
    $order_cost = filter_var($_POST["order-cost"], FILTER_SANITIZE_STRING);

    $stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ? ;");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order_details = $stmt->get_result();
} else {
    header("location: account.php");
    exit();
}
?>








<?php require "layouts/header.php"; ?>


<!-- Order Details -->

<section id="orders" class="container orders text-center my-5 py-5">
    <div class="container pt-5">
        <h2 class="font-weight-bolde text-center">Order # <?php echo $order_id; ?> Details</h2>
        <hr class="mx-auto" />
    </div>
    <table class="mt-5 pt-5 mx-auto">
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Cost/unit</th>
            <th>Subtotal</th>
        </tr>

        <?php while ($row = $order_details->fetch_assoc()): ?>

            <tr>
                <td>
                    <div class="product-info">
                        <img src="assets/imgs/<?php echo $row[
                            "product_image"
                        ]; ?>" />
                        <div class="prName mx-auto">
                            <p><?php echo $row["product_name"]; ?></p>
                        </div>
                    </div>
                </td>
                <td>
                    <span><?php echo $row["product_quantity"]; ?></span>
                </td>
                <td>
                    <span><?php echo $row["product_price"]; ?> EGP</span>
                </td>
                <td>
                    <span><?php echo $row["product_price"] *
                        $row["product_quantity"]; ?> EGP</span>
                </td>
            </tr>

        <?php endwhile; ?>

        <div class="cart-total">
            <table>
                <tr>
                    <td style="font-weight: bold;">Total</td>
                    <td></td>
                    <td></td>
                    <td><?php echo $order_cost; ?> EGP</td>
                </tr>
            </table>
        </div>

    </table>
</section>


<?php require "layouts/footer.php"; ?>
