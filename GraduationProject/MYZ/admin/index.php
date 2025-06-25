<?php

session_start();

include "../server/connection.php";

if (!isset($_SESSION["logged_in2"])) {
    header("location: ../login.php");
    exit();
} elseif (isset($_GET["logout"])) {
    unset($_SESSION["logged_in2"]);
    unset($_SESSION["admin_name"]);
    unset($_SESSION["admin_email"]);
    unset($_SESSION["admin_id"]);
    session_regenerate_id(true);

    header("location: ../login.php");
    exit();
}

$stmt2 = $conn->prepare("SELECT * FROM orders ;");
$stmt2->execute();
$orders = $stmt2->get_result();
$count_o = 0;

while ($row = $orders->fetch_assoc()) {
    $count_o += 1;
}

$stmt3 = $conn->prepare("SELECT * FROM products ;");
$stmt3->execute();
$products = $stmt3->get_result();
$count_p = 0;

while ($row = $products->fetch_assoc()) {
    $count_p += 1;
}
?>

<?php require "sidebar.php"; ?>


<div class="text-center my-2 py-3">
    <h1>
        Dashboard
    </h1>

    <table class=" mx-2 table table-bordered text-center my-5 " style="width: 98%;">
        <tbody>


            <tr>
                <th scope="row" style="text-align: center; vertical-align: middle;">Total Orders</th>
                <td style="text-align: center; vertical-align: middle;"><?php echo filter_var(
                    $count_o,
                    FILTER_SANITIZE_NUMBER_INT
                ); ?></td>
                <td style="text-align: center; vertical-align: middle;">
                    <a
                        href="orders.php"
                        class="btn btn-light btn-sm"
                        data-bs-toggle="tooltip"
                        data-bs-title="view customer"
                        data-bs-placement="top"
                        style="background-color: none ; color: black;">
                        <i class="fa-solid fa-expand"></i>
                    </a>
                </td>
            </tr>


            <tr>
                <th scope="row" style="text-align: center; vertical-align: middle;">Total Products</th>
                <td style="text-align: center; vertical-align: middle;"><?php echo filter_var(
                    $count_p,
                    FILTER_SANITIZE_NUMBER_INT
                ); ?></td>
                <td style="text-align: center; vertical-align: middle;">
                    <a
                        href="products.php"
                        class="btn btn-light btn-sm"
                        data-bs-toggle="tooltip"
                        data-bs-title="view customer"
                        data-bs-placement="top"
                        style="background-color: none ; color: black;">
                        <i class="fa-solid fa-expand"></i>
                    </a>
                </td>
            </tr>


        </tbody>
    </table>

</div>


<?php require "footer.php"; ?>
