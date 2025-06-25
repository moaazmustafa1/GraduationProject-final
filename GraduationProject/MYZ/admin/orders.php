<?php

session_start();

include "../server/connection.php";

if (!isset($_SESSION["logged_in2"])) {
    header("location: ../login.php");
    exit();
} elseif (isset($_GET["delete"])) {
    $order_id = filter_var($_GET["order_id"], FILTER_SANITIZE_NUMBER_INT);
    $stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ? ;");
    $stmt->bind_param("i", $order_id);
    if ($stmt->execute()) {
        $stmt3 = $conn->prepare("DELETE FROM order_items WHERE order_id = ? ;");
        $stmt3->bind_param("i", $order_id);
        if ($stmt3->execute()) {
            $message = "Order deleted successfully.";
        } else {
            $error = "Unexpected error: Could not delete order items.";
        }
    } else {
        $error = "Unexpected error: Could not delete order.";
    }
}

// Find how many products are available
$stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM orders;");
$stmt1->execute();
$stmt1->bind_result($total_records);
$stmt1->store_result();
$stmt1->fetch();

// Number of products per page
$total_records_per_page = 10;

$number_of_pages = ceil($total_records / $total_records_per_page);

// Get page number
if (isset($_GET["page_no"])) {
    $page_no = filter_var($_GET["page_no"], FILTER_SANITIZE_NUMBER_INT);
} else {
    $page_no = 1;
}
$exc_pg = 2; // The no. of pages needed to adjust display of pagination
$pg_ds = 1; //Displays pg_ds + 1 icons
$page_no = (int) $page_no;

$offset = ($page_no - 1) * $total_records_per_page;

$stmt2 = $conn->prepare("SELECT * FROM orders LIMIT ?, ? ;");
$stmt2->bind_param("ii", $offset, $total_records_per_page);
$stmt2->execute();
$orders = $stmt2->get_result();
?>

<?php require "sidebar.php"; ?>


<div class="text-center my-2 py-3">
    <h1>
        Orders
    </h1>

    <?php if (isset($_GET["message"]) && isset($_GET["order_id"])):
        $id = filter_var($_GET["order_id"], FILTER_SANITIZE_NUMBER_INT);

        echo "<p class=\"mt-3 text-center pt-3\" style=\"color: green;\">";
        echo "Order #$id has been updated.";
        echo "</p>";
    endif; ?>

    <?php if (isset($message)):
        $message = filter_var($message, FILTER_SANITIZE_STRING);

        echo "<p class=\"mt-3 text-center pt-3\" style=\"color: green;\">";
        echo $message;
        echo "</p>";
    endif; ?>

    <?php if (isset($error)):
        $error = filter_var($error, FILTER_SANITIZE_STRING);

        echo "<p class=\"mt-3 text-center pt-3\" style=\"color: red;\">";
        echo $error;
        echo "</p>";
    endif; ?>

    <table class=" mx-2 table table-bordered text-center my-5 align" style="width: 98%;">
        <thead>
            <tr>
                <th scope="col">Order #</th>
                <th scope="col">Cost</th>
                <th scope="col">Status</th>
                <th scope="col">User ID</th>
                <th scope="col">User's Phone</th>
                <th scope="col">Address</th>
                <th scope="col">Date</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>

            <?php while ($row = $orders->fetch_assoc()): ?>

                <tr>
                    <th scope="row" style="text-align: center; vertical-align: middle;"><?php echo $row[
                        "order_id"
                    ]; ?></th>
                    <td style="text-align: center; vertical-align: middle;"><?php echo $row[
                        "order_cost"
                    ]; ?></td>
                    <td style="text-align: center; vertical-align: middle;"><?php echo $row[
                        "order_status"
                    ]; ?></td>
                    <td style="text-align: center; vertical-align: middle;"><?php echo $row[
                        "user_id"
                    ]; ?></td>
                    <td style="text-align: center; vertical-align: middle;"><?php echo $row[
                        "user_phone"
                    ]; ?></td>
                    <td style="text-align: center; vertical-align: middle;"><?php echo $row[
                        "user_address"
                    ]; ?></td>
                    <td style="text-align: center; vertical-align: middle;"><?php echo date(
                        "g:i A",
                        strtotime($row["order_date"])
                    ) .
                        "<br>" .
                        date("d-m-Y", strtotime($row["order_date"])); ?></td>
                    <td style="text-align: center; vertical-align: middle;">
                        <a
                            href="order_details.php?order-cost=<?php echo $row[
                                "order_cost"
                            ]; ?>&order_id=<?php echo $row["order_id"]; ?>"
                            class="btn btn-light btn-sm"
                            data-bs-toggle="tooltip"
                            data-bs-title="view customer"
                            data-bs-placement="top"
                            style="background-color: none ; color: black;">
                            <i class="fa-solid fa-expand"></i>
                        </a>
                        <a
                            href="edit_order.php?order_id=<?php echo $row[
                                "order_id"
                            ]; ?>"
                            class="btn btn-sm btn-primary"
                            data-bs-toggle="tooltip"
                            data-bs-title="edit customer"
                            data-bs-placement="top">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                        <a
                            href="orders.php?delete=1&order_id=<?php echo $row[
                                "order_id"
                            ]; ?>"
                            class="btn btn-danger btn-sm"
                            data-bs-toggle="tooltip"
                            data-bs-title="delete customer"
                            data-bs-placement="top">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>

            <?php endwhile; ?>

        </tbody>
    </table>

    <div class="center">
        <div class="pagination">
            <a href="<?php if ($page_no > 1) {
                $previous_page = $page_no - 1;
                echo "orders.php?page_no=" . $previous_page;
            } else {
                echo "#";
            } ?>">&laquo;</a>

            <?php
            if ($number_of_pages >= $exc_pg):
                $s = $page_no;
                if ($s >= $number_of_pages - $pg_ds):
                    $s = $number_of_pages - $pg_ds;
                    $s = (int) $s;
                endif;
                $u = $page_no + $pg_ds;
                if ($u >= $number_of_pages):
                    $u = $number_of_pages;
                endif;
            else:
                $s = 1;
                $u = $number_of_pages;
            endif;

            if ($number_of_pages >= $exc_pg && $s !== 1):
                $one = 1;
                echo "<a href=\"orders.php?page_no=$one\"";

                echo ">$one</a>";
            endif;

            if ($number_of_pages >= $exc_pg && $page_no !== 1):
                $v = $page_no - $pg_ds - 1;
                if ($v <= 0):
                    $v = 1;
                endif;
                echo "<a href=\"orders.php?page_no=$v\"";

                echo "> ... </a>";
            endif;

            for ($i = $s; $i <= $u; $i++):
                echo "<a href=\"orders.php?page_no=" . $i . '"';

                if ($i === $page_no):
                    echo ' class="active"';
                endif;

                echo ">" . $i . "</a>";
            endfor;

            if (
                $number_of_pages >= $exc_pg &&
                $page_no !== $number_of_pages - $pg_ds &&
                $u !== $number_of_pages
            ):
                $v = $page_no + $pg_ds + 1;
                if ($v >= $number_of_pages):
                    $v = $number_of_pages;
                endif;
                echo "<a href=\"orders.php?page_no=$v\"";

                echo "> ... </a>";
            endif;

            if ($number_of_pages >= $exc_pg && $u !== $number_of_pages):
                echo "<a href=\"orders.php?page_no=$number_of_pages\"";

                echo ">$number_of_pages</a>";
            endif;
            ?>

            <a href="<?php if ($page_no < $number_of_pages) {
                $next_page = $page_no + 1;
                echo "orders.php?page_no=" . $next_page;
            } else {
                echo "#";
            } ?>">&raquo;</a>
        </div>
    </div>

</div>


<?php require "footer.php"; ?>
