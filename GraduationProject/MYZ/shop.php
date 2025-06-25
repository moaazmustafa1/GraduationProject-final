<?php
$shop = true;
require "server/connection.php";
require "server/AddToCart.php";

// Get page number
if (isset($_GET["page_no"])) {
    $page_no = filter_var($_GET["page_no"], FILTER_SANITIZE_NUMBER_INT);
} else {
    $page_no = 1;
}

$exc_pg = 2; // The no. of pages needed to adjust display of pagination
$pg_ds = 1; //Displays pg_ds + 1 icons
$page_no = (int) $page_no;

if (isset($_GET["search"])) {
    // We can use POST for more security

    $category = filter_var($_GET["category"], FILTER_SANITIZE_STRING);
    $min_price = filter_var($_GET["min_price"], FILTER_SANITIZE_NUMBER_INT);
    $max_price = filter_var($_GET["max_price"], FILTER_SANITIZE_NUMBER_INT);

    if ($category !== "none"):
        $stmt1 = $conn->prepare(
            "SELECT COUNT(*) AS total_records FROM products WHERE  product_category = ? AND product_price >= ? AND product_price <= ? ;"
        );
        $stmt1->bind_param("sii", $category, $min_price, $max_price);
        list($offset, $total_records_per_page, $number_of_pages) = CalcPage(
            $stmt1,
            $page_no
        );

        $stmt2 = $conn->prepare(
            "SELECT product_id, product_image, product_name, product_price FROM products WHERE  product_category = ? AND product_price >= ? AND product_price <= ? LIMIT ?, ? ;"
        );
        $stmt2->bind_param(
            "siiii",
            $category,
            $min_price,
            $max_price,
            $offset,
            $total_records_per_page
        );
    else:
        $stmt1 = $conn->prepare(
            "SELECT COUNT(*) AS total_records FROM products WHERE product_price >= ? AND product_price <= ? ;"
        );
        $stmt1->bind_param("ii", $min_price, $max_price);
        list($offset, $total_records_per_page, $number_of_pages) = CalcPage(
            $stmt1,
            $page_no
        );

        $stmt2 = $conn->prepare(
            "SELECT product_id, product_image, product_name, product_price FROM products WHERE product_price >= ? AND product_price <= ? LIMIT ?, ? ;"
        );
        $stmt2->bind_param(
            "iiii",
            $min_price,
            $max_price,
            $offset,
            $total_records_per_page
        );
    endif;
} elseif (isset($_GET["reset"])) {
    header("location: shop.php");
    exit();
} else {
    // Find how many products are available
    $stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM products;");
    list($offset, $total_records_per_page, $number_of_pages) = CalcPage(
        $stmt1,
        $page_no
    );

    $stmt2 = $conn->prepare("SELECT * FROM products LIMIT ?, ? ;");
    $stmt2->bind_param("ii", $offset, $total_records_per_page);
}

if ($stmt2->execute()):
    $products = $stmt2->get_result();
else:
    echo '<script>alert("An error has occured. Please try again.");</script>';
    header("location: shop.php");
    exit();
endif;

function CalcPage($stmt1, $page_no)
{
    $stmt1->execute();
    $stmt1->bind_result($total_records);
    $stmt1->store_result();
    $stmt1->fetch();

    // Number of products per page
    $total_records_per_page = 2;

    $number_of_pages = ceil($total_records / $total_records_per_page);

    $offset = ($page_no - 1) * $total_records_per_page;
    return [$offset, $total_records_per_page, $number_of_pages];
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
            grid-column: 1 / 4;
            grid-row: 3 / 4 ;
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

        <form action="shop.php" method="get">
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

            <div class="form-group my-3 mx-3">
            <input type="submit" name="reset" value="Reset" class="btn btn-danger">
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
                <h4 class="p-price"><?php echo $row[
                    "product_price"
                ]; ?> EGP</h4>

                    <form method="post" action="">
                      <input type="hidden" name="product_id" value="<?php echo $row[
                          "product_id"
                      ]; ?>">
                      <input type="hidden" name="product_image" value="<?php echo $row[
                          "product_image"
                      ]; ?>">
                      <input type="hidden" name="product_name" value="<?php echo $row[
                          "product_name"
                      ]; ?>">
                      <input type="hidden" name="product_price" value="<?php echo $row[
                          "product_price"
                      ]; ?>">
                      <input type="hidden" name="product_quantity" value="1" />
                      <button class="buy btn-primary" type="submit" name="add-to-cart">ADD TO CART</button>
                    </form>

            </div>

            <?php endwhile; ?>

            <?php if (!isset($_GET["search"]) && $number_of_pages !== 0.0): ?>
            <div class="center my-5">
                <div class="pagination">
                    <a href="<?php if ($page_no > 1) {
                        $previous_page = $page_no - 1;
                        echo "shop.php?page_no=" . $previous_page;
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
                        echo "<a href=\"shop.php?page_no=$one\"";

                        echo ">$one</a>";
                    endif;

                    if ($number_of_pages >= $exc_pg && $page_no !== 1):
                        $v = $page_no - $pg_ds - 1;
                        if ($v <= 0):
                            $v = 1;
                        endif;
                        echo "<a href=\"shop.php?page_no=$v\"";

                        echo "> ... </a>";
                    endif;

                    for ($i = $s; $i <= $u; $i++):
                        echo "<a href=\"shop.php?page_no=" . $i . '"';

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
                        echo "<a href=\"shop.php?page_no=$v\"";

                        echo "> ... </a>";
                    endif;

                    if ($number_of_pages >= $exc_pg && $u !== $number_of_pages):
                        echo "<a href=\"shop.php?page_no=$number_of_pages\"";

                        echo ">$number_of_pages</a>";
                    endif;
                    ?>

                    <a href="<?php if ($page_no < $number_of_pages) {
                        $next_page = $page_no + 1;
                        echo "shop.php?page_no=" . $next_page;
                    } else {
                        echo "#";
                    } ?>">&raquo;</a>
                </div>
            </div>
            <?php endif; ?>

            <?php if (
                isset($_GET["search"]) &&
                isset($_GET["category"]) &&
                isset($_GET["min_price"]) &&
                isset($_GET["max_price"]) &&
                $number_of_pages !== 0.0
            ): ?>
            <div class="center my-5">
                <div class="pagination">
                    <a href="<?php if ($page_no > 1) {
                        $previous_page = $page_no - 1;
                        echo "shop.php?page_no=$previous_page&category=$category&min_price=$min_price&max_price=$max_price&search=Filter";
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
                        echo "<a href=\"shop.php?page_no=$one&category=$category&min_price=$min_price&max_price=$max_price&search=Filter\"";

                        echo ">$one</a>";
                    endif;

                    if ($number_of_pages >= $exc_pg && $page_no !== 1):
                        $v = $page_no - $pg_ds - 1;
                        if ($v <= 0):
                            $v = 1;
                        endif;
                        echo "<a href=\"shop.php?page_no=$v&category=$category&min_price=$min_price&max_price=$max_price&search=Filter\"";

                        echo "> ... </a>";
                    endif;

                    for ($i = $s; $i <= $u; $i++):
                        echo "<a href=\"shop.php?page_no=$i&category=$category&min_price=$min_price&max_price=$max_price&search=Filter\"";

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
                        echo "<a href=\"shop.php?page_no=$v&category=$category&min_price=$min_price&max_price=$max_price&search=Filter\"";

                        echo "> ... </a>";
                    endif;

                    if ($number_of_pages >= $exc_pg && $u !== $number_of_pages):
                        echo "<a href=\"shop.php?page_no=$number_of_pages&category=$category&min_price=$min_price&max_price=$max_price&search=Filter\"";

                        echo ">$number_of_pages</a>";
                    endif;
                    ?>

                    <a href="<?php if ($page_no < $number_of_pages) {
                        $next_page = $page_no + 1;
                        echo "shop.php?page_no=$next_page&category=$category&min_price=$min_price&max_price=$max_price&search=Filter";
                    } else {
                        echo "#";
                    } ?>">&raquo;</a>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <?php if ($number_of_pages === 0.0): ?>
            <div class="container text-center mx-auto py-auto">
                <span>No products found.</span>
            </div>
        <?php endif; ?>
    </section>

<?php require "layouts/footer.php"; ?>
