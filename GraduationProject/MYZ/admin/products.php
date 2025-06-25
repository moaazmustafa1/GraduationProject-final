<?php

session_start();

include "../server/connection.php";

if (!isset($_SESSION["logged_in2"])) {
    header("location: ../login.php");
    exit();
} elseif (isset($_GET["delete"]) && isset($_GET["product_id"])) {
    $product_id = filter_var($_GET["product_id"], FILTER_SANITIZE_NUMBER_INT);
    $stmt3 = $conn->prepare("SELECT * FROM products WHERE product_id = ? ;");
    $stmt3->bind_param("i", $product_id);
    $stmt3->execute();
    $delete_product = $stmt3->get_result();

    while ($row1 = $delete_product->fetch_assoc()):
        $delete_image = $row1["product_image"];
        $delete_image2 = $row1["product_image2"];
        $delete_image3 = $row1["product_image3"];
        $delete_image4 = $row1["product_image4"];
        unlink("../assets/imgs/$delete_image");
        unlink("../assets/imgs/$delete_image2");
        unlink("../assets/imgs/$delete_image3");
        unlink("../assets/imgs/$delete_image4");
    endwhile;

    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ? ;");
    $stmt->bind_param("i", $product_id);
    if ($stmt->execute()) {
        $message = "Product deleted successfully.";
    } else {
        $error = "Unexpected error: Could not delete product.";
    }
}

// Find how many products are available
$stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM products;");
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

$stmt2 = $conn->prepare("SELECT * FROM products LIMIT ?, ? ;");
$stmt2->bind_param("ii", $offset, $total_records_per_page);
$stmt2->execute();
$products = $stmt2->get_result();
?>

<?php require "sidebar.php"; ?>


<div class="text-center my-2 py-3">
    <h1>
        Products
    </h1>

    <?php if (isset($_GET["message"]) && isset($_GET["product_id"])):
        $id = filter_var($_GET["product_id"], FILTER_SANITIZE_NUMBER_INT);

        echo "<p class=\"mt-3 text-center pt-3\" style=\"color: green;\">";
        echo "Product #$id has been updated.";
        echo "</p>";
    elseif (isset($_GET["message"])):
        echo "<p class=\"mt-3 text-center pt-3\" style=\"color: green;\">";
        echo $_GET["message"];
        echo "</p>";
    endif; ?>

    <?php if (isset($message)):
        echo "<p class=\"mt-3 text-center pt-3\" style=\"color: green;\">";
        echo $message;
        echo "</p>";
    endif; ?>

    <?php if (isset($error) || isset($_GET["error"])):
        $m = $_GET["error"] ?? $error;
        $m = filter_var($m, FILTER_SANITIZE_STRING);

        echo "<p class=\"mt-3 text-center pt-3\" style=\"color: red;\">";
        echo $m;
        echo "</p>";
    endif; ?>


    <table class=" mx-2 table table-bordered text-center my-5 align" style="width: 98%;">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Image</th>
                <th scope="col">Name</th>
                <th scope="col">Category</th>
                <th scope="col">Color</th>
                <th scope="col">Description</th>
                <th scope="col">Price</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>

            <?php while ($row = $products->fetch_assoc()): ?>

                <tr>
                    <th scope="row" style="text-align: center; vertical-align: middle;"><?php echo $row[
                        "product_id"
                    ]; ?></th>
                    <td style="text-align: center; vertical-align: middle;"><img style="height: 65px;" src="../assets/imgs/<?php echo $row[
                        "product_image"
                    ]; ?>"></td>
                    <td style="text-align: center; vertical-align: middle;"><?php echo $row[
                        "product_name"
                    ]; ?></td>
                    <td style="text-align: center; vertical-align: middle;"><?php echo $row[
                        "product_category"
                    ]; ?></td>
                    <td style="text-align: center; vertical-align: middle;"><?php echo $row[
                        "product_color"
                    ]; ?></td>
                    <td style="text-align: center; vertical-align: middle;"><?php echo $row[
                        "product_description"
                    ]; ?></td>
                    <td style="text-align: center; vertical-align: middle;"><?php echo $row[
                        "product_price"
                    ]; ?> EGP</td>
                    <td style="text-align: center; vertical-align: middle;">
                        <a
                            href="edit_product.php?product_id=<?php echo $row[
                                "product_id"
                            ]; ?>"
                            class="btn btn-sm btn-primary"
                            data-bs-toggle="tooltip"
                            data-bs-title="edit customer"
                            data-bs-placement="top">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                        <a
                            href="products.php?delete=1&product_id=<?php echo $row[
                                "product_id"
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
                echo "products.php?page_no=" . $previous_page;
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
                echo "<a href=\"products.php?page_no=$one\"";

                echo ">$one</a>";
            endif;

            if ($number_of_pages >= $exc_pg && $page_no !== 1):
                $v = $page_no - $pg_ds - 1;
                if ($v <= 0):
                    $v = 1;
                endif;
                echo "<a href=\"products.php?page_no=$v\"";

                echo "> ... </a>";
            endif;

            for ($i = $s; $i <= $u; $i++):
                echo "<a href=\"products.php?page_no=" . $i . '"';

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
                echo "<a href=\"products.php?page_no=$v\"";

                echo "> ... </a>";
            endif;

            if ($number_of_pages >= $exc_pg && $u !== $number_of_pages):
                echo "<a href=\"products.php?page_no=$number_of_pages\"";

                echo ">$number_of_pages</a>";
            endif;
            ?>

            <a href="<?php if ($page_no < $number_of_pages) {
                $next_page = $page_no + 1;
                echo "products.php?page_no=" . $next_page;
            } else {
                echo "#";
            } ?>">&raquo;</a>
        </div>
    </div>

</div>


<?php require "footer.php"; ?>
