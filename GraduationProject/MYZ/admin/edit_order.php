<?php

session_start();

include "../server/connection.php";

if (!isset($_SESSION["logged_in2"])) {
    header("location: ../login.php");
    exit();
} elseif (isset($_GET["order_id"])) {
    $order_id = $_GET["order_id"];
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? ;");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order = $stmt->get_result();
} elseif (isset($_POST["confirm"])) {
    $order_id = $_POST["id"];
    $status = $_POST["status"];

    // No need to validate status being empty, since options are already preset to unpaid, paid or delivered.

    $stmt = $conn->prepare(
        "UPDATE orders SET order_status = ? WHERE order_id = ? ;"
    );

    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        header("location: orders.php?message=true&order_id=$order_id");
        exit();
    } else {
        $error = "Unexpected error: Could not edit order.";
    }
} else {
    header("location: orders.php");
    exit();
}
?>

<!--/////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<?php require "sidebar.php"; ?>


<div class="text-center my-2 py-3">
    <h1>
        Edit Order
    </h1>

    <?php if (isset($error)):
        echo "<p class=\"mt-3 text-center pt-3\" style=\"color: red;\">";
        echo $error;
        echo "</p>";
    endif; ?>
    <?php while ($row = $order->fetch_assoc()): ?>

        <div class="container text-center my-5 align mx-auto">
            <form id="order-form" method="post" action="edit_order.php">

                <div class="row g-3 align-items-center my-5">
                    <div class="col-auto">
                        <label for="order-id" class="col-form-label">Order #</label>
                    </div>
                    <div class="col-auto">
                        <span class="form-text">
                            <?php echo $row["order_id"]; ?>
                        </span>
                    </div>
                    <div class="col-auto">
                        <input
                            type="hidden"
                            class="form-control"
                            id="order-id"
                            name="id"
                            value="<?php echo $row["order_id"]; ?>" />
                    </div>
                </div>


                <div class="row g-3 align-items-center my-5">
                    <div class="col-auto">
                        <label for="order-cost" class="col-form-label">Cost : </label>
                    </div>
                    <div class="col-auto">
                        <div class="col-auto">
                            <span class="form-text">
                                <?php echo $row["order_cost"]; ?> EGP
                            </span>
                        </div>
                    </div>
                </div>


                <div class="row g-3 align-items-center my-5">
                    <div class="col-auto">
                        <label for="order-status" class="col-form-label">Status : </label>
                    </div>
                    <div class="col-auto">
                        <select class="form-select"
                            name="status"
                            id="order-status"
                            required>
                            <option value="unpaid" <?php if (
                                $row["order_status"] === "unpaid"
                            ) {
                                echo "selected";
                            } ?>>Unpaid</option>
                            <option value="paid" <?php if (
                                $row["order_status"] === "paid"
                            ) {
                                echo "selected";
                            } ?>>Paid</option>
                            <option value="delivered" <?php if (
                                $row["order_status"] === "delivered"
                            ) {
                                echo "selected";
                            } ?>>Delivered</option>
                        </select>
                    </div>
                </div>


                <div class="row g-3 align-items-center my-5">
                    <div class="col-auto">
                        <label for="user-id" class="col-form-label">User ID : </label>
                    </div>
                    <div class="col-auto">
                        <div class="col-auto">
                            <span class="form-text">
                                <?php echo $row["user_id"]; ?>
                            </span>
                        </div>
                    </div>
                </div>



                <div class="row g-3 align-items-center my-5">
                    <div class="col-auto">
                        <label for="users-phone" class="col-form-label">User's Phone : </label>
                    </div>
                    <div class="col-auto">
                        <div class="col-auto">
                            <span class="form-text">
                                <?php echo $row["user_phone"]; ?>
                            </span>
                        </div>
                    </div>
                </div>


                <div class="row g-3 align-items-center my-5">
                    <div class="col-auto">
                        <label for="address" class="col-form-label">Address : </label>
                    </div>
                    <div class="col-auto">
                        <div class="col-auto">
                            <span class="form-text">
                                <?php echo $row["user_address"]; ?>
                            </span>
                        </div>
                    </div>
                </div>


                <div class="row g-3 align-items-center my-5">
                    <div class="col-auto">
                        <label for="date" class="col-form-label">Date : </label>
                    </div>
                    <div class="col-auto">
                        <div class="col-auto">
                            <span class="form-text">
                                <?php echo date(
                                    "d-m-Y",
                                    strtotime($row["order_date"])
                                ) .
                                    " ----- " .
                                    date(
                                        "g:i A",
                                        strtotime($row["order_date"])
                                    ); ?>
                            </span>
                        </div>
                    </div>
                </div>


                <div class="row g-3 align-items-center mt-5 mx-auto">
                    <div class="col-auto">
                        <input
                            type="submit"
                            class="btn btn-primary"
                            name="confirm"
                            value="Confirm" />
                    </div>
                    <div class="col-auto">
                        <a class="btn btn-danger" href="orders.php">Cancel</a>
                    </div>
                </div>


            </form>
        </div>

    <?php endwhile; ?>



</div>


<?php require "footer.php"; ?>



<!-- class=<php if (($i) === $page_no) {
            echo '"active"';
        } else {
            echo '""';
        } ?> -->
