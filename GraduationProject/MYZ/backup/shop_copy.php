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
        header("location: shop_copy.php");
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
    if (isset($_GET["page_no"])) {
        $page_no = filter_var($_GET["page_no"], FILTER_SANITIZE_NUMBER_INT);
    } else {
        $page_no = 1;
    }

    $offset = ($page_no - 1) * $total_records_per_page;

    $adjacent = "2";

    $stmt2 = $conn->prepare("SELECT * FROM products LIMIT ?, ? ;");
    $stmt2->bind_param("ii", $offset, $total_records_per_page);
    $stmt2->execute();
    $products = $stmt2->get_result();
}
if (session_status() !== PHP_SESSION_ACTIVE):
    session_start();
endif;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MYZ</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="assets/font-awesome/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <style>
        body{
            height: 100vh;
            display: grid;
            grid-template-columns: 200px 1fr;
            grid-template-rows: 103px 1fr 1fr;
        }

        .navbar{
            grid-column: 1 / 3;
            grid-row: 1 / 2 ;
        }
        .filter{
            grid-column: 1 / 2;
            grid-row: 2 / 3 ;
        }
        .products{
            grid-column: 2/ 3;
            grid-row: 2 / 3 ;
        }
        footer{
            grid-column: 1/ 3;
            grid-row: 3 /4 ;
        }

        #shop{
            margin-left: auto !important ;
        }

    </style>
</head>

<body>
    <!--Navigation Bar-->
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 fixed-top">
        <div class="container">
            <a href="index.php"><img id="LogoImg" src="assets/imgs/logo.jpg" /></a>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div
                class="collapse navbar-collapse nav-buttons"
                id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="shop.php">Shop</a>
                    </li>

                    <li id="Contact" class="nav-item">
                        <a class="nav-link" href="contact.php">Contact Us</a>
                    </li>

                    <li class="nav-item font-icon">
                        <a href="cart.php"><i class="fa-solid fa-cart-shopping">
                                <span><?php if (
                                    isset($_SESSION["total_quantities"]) &&
                                    $_SESSION["total_quantities"] !== 0
                                ) {
                                    echo " " . $_SESSION["total_quantities"];
                                } ?></span>
                            </i></a>
                    </li>

            <?php if (isset($_SESSION["logged_in"])): ?>

            <li class="nav-item font-icon dropdown">
                      <a class="dropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="fa-solid fa-user"></i>
                      </a>
                      <ul class="dropdown-menu">
                        <li>
                            <a href="account.php" class="dropdown-item" >
                                Account
                            </a>
                        </li>
                        <li>
                              <a href="account.php?logout=1" class="dropdown-item">
                            Logout
                              </a>
                        </li>

                      </ul>
            </li>
            <?php else: ?>

                    <li class="nav-item font-icon">
                        <a href="login.php"><i class="fa-solid fa-user"></i></a>
                    </li>

            <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>


    <!-- Filter -->
    <section class="filter" id="search">
    <div class="container mt-3 py-3">
        <p style="font-weight: bold;">Filter Products</p>
        <hr>
    </div>

    <form action="shop_copy.php" method="post">
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
            <label class="form-check-label" for="category_one">None</label>
            </div>

            <div class="form_check">
            <input class="form-check-input" type="radio" name="category" id="category_two" value="Sofa" <?php if (
                isset($category) &&
                $category === "Sofa"
            ) {
                echo "checked";
            } ?>>
            <label class="form-check-label" for="category_two">Sofa</label>
            </div>

            <div class="form_check">
            <input class="form-check-input" type="radio" name="category" id="category_three" value="Bed" <?php if (
                isset($category) &&
                $category === "Bed"
            ) {
                echo "checked";
            } ?>>
            <label class="form-check-label" for="category_three">Bed</label>
            </div>

            <div class="form_check">
            <input class="form-check-input" type="radio" name="category" id="category_four" value="Armchair" <?php if (
                isset($category) &&
                $category === "Armchair"
            ) {
                echo "checked";
            } ?>>
            <label class="form-check-label" for="category_four">Armchair</label>
            </div>

            <div class="form_check">
            <input class="form-check-input" type="radio" name="category" id="category_five" value="Table" <?php if (
                isset($category) &&
                $category === "Table"
            ) {
                echo "checked";
            } ?>>
            <label class="form-check-label" for="category_five">Table</label>
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
    <section id="shop" class="products my-5 py-5">
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
        <div class="center my-5">
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
