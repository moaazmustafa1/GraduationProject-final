<?php

session_start();

include "../server/connection.php";

if (!isset($_SESSION["logged_in2"])) {
    header("location: ../login.php");
    exit();
} elseif (isset($_GET["order_id"])) {
    $order_id = filter_var($_GET["order_id"], FILTER_SANITIZE_NUMBER_INT);
    $order_cost = (int) filter_var(
        $_GET["order-cost"],
        FILTER_SANITIZE_NUMBER_INT
    );
    $order_cost = fdiv($order_cost, 100);

    $stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ? ;");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order_details = $stmt->get_result();
} else {
    header("location: orders.php");
    exit();
}
?>

<?php require "sidebar.php"; ?>


<div class="text-center my-2 py-3">
    <h1>
        Order #<?php echo $order_id; ?> Details
    </h1>

    <table class=" mx-2 table table-bordered text-center my-5 align" style="width: 98%;">
        <thead>
            <tr>
                <th scope="col">User ID</th>
                <th scope="col">Product ID</th>
                <th scope="col">Image</th>
                <th scope="col">Name</th>
                <th scope="col">Quantity</th>
                <th scope="col">Cost/unit</th>
                <th scope="col">Date</th>
                <th scope="col">Subtotal</th>
            </tr>
        </thead>
        <tbody>

            <?php while ($row = $order_details->fetch_assoc()): ?>

                <tr>
                    <th scope="row" style="text-align: center; vertical-align: middle;"><?php echo $row[
                        "user_id"
                    ]; ?></th>
                    <td style="text-align: center; vertical-align: middle;"><?php echo $row[
                        "product_id"
                    ]; ?></td>
                    <td style="text-align: center; vertical-align: middle;"><img style="height: 65px;" src="../assets/imgs/<?php echo $row[
                        "product_image"
                    ]; ?>"></td>
                    <td style="text-align: center; vertical-align: middle;"><?php echo $row[
                        "product_name"
                    ]; ?></td>
                    <td style="text-align: center; vertical-align: middle;"><?php echo $row[
                        "product_quantity"
                    ]; ?></td>
                    <td style="text-align: center; vertical-align: middle;"><?php echo $row[
                        "product_price"
                    ]; ?> EGP</td>
                    <td style="text-align: center; vertical-align: middle;"><?php echo date(
                        "g:i A",
                        strtotime($row["order_date"])
                    ) .
                        "<br>" .
                        date("d-m-Y", strtotime($row["order_date"])); ?></td>
                    <td style="text-align: center; vertical-align: middle;"><?php echo $row[
                        "product_quantity"
                    ] * $row["product_price"]; ?> EGP</td>
                </tr>

            <?php endwhile; ?>

            <tr>
                <th scope="row" style="text-align: center; vertical-align: middle;"></th>
                <td style="text-align: center; vertical-align: middle;"></td>
                <td style="text-align: center; vertical-align: middle;"></td>
                <td style="text-align: center; vertical-align: middle;"></td>
                <td style="text-align: center; vertical-align: middle;"></td>
                <td style="text-align: center; vertical-align: middle;"></td>
                <td style="text-align: center; vertical-align: middle;"></td>
                <td style="text-align: center; vertical-align: middle;"><?php echo $order_cost; ?> EGP</td>
            </tr>

        </tbody>
    </table>



</div>


<?php require "footer.php"; ?>
