<?php

session_start();

include "../server/connection.php";

if (!isset($_SESSION["logged_in2"])):
    header("location: ../login.php");
    exit();
elseif (isset($_GET["product_id"])):
    $product_id = filter_var($_GET["product_id"], FILTER_SANITIZE_NUMBER_INT);
    $product = displayProducts($product_id, $conn);
elseif (isset($_POST["confirm"])):
    $product_id = filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $category = filter_var($_POST["category"], FILTER_SANITIZE_STRING);
    $color = filter_var($_POST["color"], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST["description"], FILTER_SANITIZE_STRING);
    $price = filter_var(
        $_POST["price"],
        FILTER_SANITIZE_NUMBER_FLOAT,
        FILTER_FLAG_ALLOW_FRACTION
    );

    if (empty($name)):
        $error = "Please fill in the name field";
        $product = displayProducts($product_id, $conn);
    elseif (empty($category)):
        $error = "Please fill in the category field";
        $product = displayProducts($product_id, $conn);
    elseif (empty($color)):
        $error = "Please fill in the color field";
        $product = displayProducts($product_id, $conn);
    elseif (empty($description)):
        $error = "Please fill in the description field";
        $product = displayProducts($product_id, $conn);
    elseif (empty($price)):
        $error = "Please fill in the price field";
        $product = displayProducts($product_id, $conn);
    else:
        $stmt = $conn->prepare("UPDATE products SET
    product_name = ? ,
    product_category = ? ,
    product_description = ? ,
    product_price = ? ,
    product_color = ?
    WHERE product_id = ? ;");

        $stmt->bind_param(
            "sssdsi",
            $name,
            $category,
            $description,
            $price,
            $color,
            $product_id
        );

        if ($stmt->execute()):
            header(
                "location: products.php?message=true&product_id=$product_id"
            );
            exit();
        else:
            $error = "Unexpected error: Could not edit product.";
        endif;
    endif;
else:
    header("location: products.php");
    exit();
endif;

function displayProducts($productID, $conn)
{
    if (empty($productID)):
        header("location: products.php?error=Invalid product ID.");
        exit();
    else:
        $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ? ;");
        $stmt->bind_param("i", $productID);
    endif;

    if ($stmt->execute()):
        $product = $stmt->get_result();
        return $product;
    else:
        header("location: products.php?error=Something went wrong.");
        exit();
    endif;
}
?>

<!--/////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<?php require "sidebar.php"; ?>


<div class="text-center my-2 py-3">
    <h1>
        Edit Product
    </h1>

    <?php if (isset($message)):
        echo "<p class=\"mt-3 text-center pt-3\" style=\"color: green;\">";
        echo $message;
        echo "</p>";
    endif; ?>


    <?php if (isset($_GET["message"])):
        echo "<p class=\"mt-3 text-center pt-3\" style=\"color: green;\">";
        echo $_GET["message"];
        echo "</p>";
    endif; ?>

    <?php if (isset($error)):
        echo "<p class=\"mt-3 text-center pt-3\" style=\"color: red;\">";
        echo $error;
        echo "</p>";
    endif; ?>

    <?php while ($row = $product->fetch_assoc()): ?>

        <div class="container text-center my-5 align mx-auto">
            <form id="product-form" method="post" action="edit_product.php">

                <div class="row g-3 align-items-center my-5">
                    <div class="col-auto">
                        ID :
                    </div>
                    <div class="col-auto">
                        <span class="form-text">
                            <?php echo $row["product_id"]; ?>
                        </span>
                    </div>
                    <div class="col-auto">
                        <input
                            type="hidden"
                            class="form-control"
                            id="product-id"
                            name="id"
                            value="<?php echo $row["product_id"]; ?>" />
                    </div>
                </div>


                <div class="row g-3 align-items-center my-5">
                    <div class="col-auto">
                        Image :
                    </div>
                    <div class="col-auto">
                        <img style="height: 65px;" src="../assets/imgs/<?php echo $row[
                            "product_image"
                        ]; ?>">
                    </div>
                    <div class="col-auto">
                        <a class="form-text" href=
                            "edit_image.php?product_id=<?php echo $row[
                                "product_id"
                            ]; ?>&product_image=<?php echo $row[
    "product_image"
]; ?>&name=<?php echo $row["product_name"]; ?>"
                        style="text-decoration: underline;">
                            Edit Images
                        </a>
                    </div>
                </div>


                <div class="row g-3 align-items-center my-5">
                    <div class="col-auto">
                        <label for="product-name" class="col-form-label">Name : </label>
                    </div>
                    <div class="col-auto">
                        <input
                            type="text"
                            class="form-control"
                            id="product-name"
                            name="name"
                            value="<?php echo $row["product_name"]; ?>"
                            required />
                    </div>
                </div>


                <div class="row g-3 align-items-center my-5">
                    <div class="col-auto">
                        <label for="product-category" class="col-form-label">Category : </label>
                    </div>
                    <div class="col-auto">
                        <select class="form-select"
                            name="category"
                            id="product-category"
                            required>
                            <option value="Sofa" <?php if (
                                $row["product_category"] === "Sofa"
                            ) {
                                echo "selected";
                            } ?>>Sofa</option>
                            <option value="Bed" <?php if (
                                $row["product_category"] === "Bed"
                            ) {
                                echo "selected";
                            } ?>>Bed</option>
                            <option value="Armchair" <?php if (
                                $row["product_category"] === "Armchair"
                            ) {
                                echo "selected";
                            } ?>>Armchair</option>
                            <option value="Table" <?php if (
                                $row["product_category"] === "Table"
                            ) {
                                echo "selected";
                            } ?>>Table</option>
                        </select>
                    </div>
                </div>


                <div class="row g-3 align-items-center my-5">
                    <div class="col-auto">
                        <label for="product-color" class="col-form-label">Color : </label>
                    </div>
                    <div class="col-auto">
                        <input
                            type="text"
                            class="form-control"
                            id="product-color"
                            name="color"
                            value="<?php echo $row["product_color"]; ?>"
                            required />
                    </div>
                </div>



                <div class="row g-3 align-items-center my-5">
                    <div class="col-auto">
                        <label for="product-description" class="col-form-label">Description : </label>
                    </div>
                    <div class="col-auto">
                        <input
                            type="text"
                            class="form-control"
                            style="width: 275%;"
                            id="product-description"
                            name="description"
                            value="<?php echo $row["product_description"]; ?>"
                            required />
                    </div>
                </div>


                <div class="row g-3 align-items-center my-5">
                    <div class="col-auto">
                        <label for="product-price" class="col-form-label">Price : </label>
                    </div>
                    <div class="col-auto">
                        <input
                            type="number"
                            class="form-control"
                            id="product-price"
                            name="price"
                            value="<?php echo $row["product_price"]; ?>"
                            min="0"
                            step="0.01"
                            required />
                    </div>
                    <div class="col-auto">
                        <span class="form-text">
                            EGP
                        </span>
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
                        <a class="btn btn-danger" href="products.php">Cancel</a>
                    </div>
                </div>


            </form>
        </div>

    <?php endwhile; ?>



</div>


<?php require "footer.php"; ?>
