<?php

session_start();

include "../server/connection.php";

if (!isset($_SESSION["logged_in2"])) {
    header("location: ../login.php");
    exit();
} elseif (isset($_POST["add"])) {
    $name = $_POST["name"];
    $category = $_POST["category"];
    $color = $_POST["color"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $offer = (float) $_POST["offer"];

    $image_T = $_FILES["image"]["tmp_name"];
    $image2_T = $_FILES["image2"]["tmp_name"];
    $image3_T = $_FILES["image3"]["tmp_name"];
    $image4_T = $_FILES["image4"]["tmp_name"];

    // $image_N = $_FILES["image"]["name"];
    // $image2_N = $_FILES["image2"]["name"];
    // $image3_N = $_FILES["image3"]["name"];
    // $image4_N = $_FILES["image4"]["name"];

    $image_S = $_FILES["image"]["size"];
    $image2_S = $_FILES["image2"]["size"];
    $image3_S = $_FILES["image3"]["size"];
    $image4_S = $_FILES["image4"]["size"];

    $image_MIME = $_FILES["image"]["type"];
    $image2_MIME = $_FILES["image2"]["type"];
    $image3_MIME = $_FILES["image3"]["type"];
    $image4_MIME = $_FILES["image4"]["type"];

    if (empty($name)):
        $error = "Please fill in the name field.";
    elseif (empty($category)):
        $error = "Please fill the category field.";
    elseif (empty($color)):
        $error = "Please fill in the color field.";
    elseif (empty($description)):
        $error = "Please fill in the description field.";
    elseif (empty($price)):
        $error = "Please fill in the price field.";
    elseif (empty($offer) && $offer !== 0.0):
        $error = "Please fill in the offer field.";
    elseif (empty($image_T)):
        $error = "Please choose image 1.";
    elseif (empty($image2_T)):
        $error = "Please choose image 2.";
    elseif (empty($image3_T)):
        $error = "Please choose image 3.";
    elseif (empty($image4_T)):
        $error = "Please choose image 4.";
    else:
        $imageExtension = end(explode("/", $image_MIME));
        $image2Extension = end(explode("/", $image2_MIME));
        $image3Extension = end(explode("/", $image3_MIME));
        $image4Extension = end(explode("/", $image4_MIME));

        $allowedExtenstions = ["jpg", "jpeg", "png"];

        $allowedMIME = ["image/jpg", "image/jpeg", "image/png"];

        if (!in_array($imageExtension, $allowedExtenstions)):
            $error =
                "Only .jpeg, .jpg, and .png images are accepted for image 1";
        elseif (!in_array($image2Extension, $allowedExtenstions)):
            $error =
                "Only .jpeg, .jpg, and .png images are accepted for image 2";
        elseif (!in_array($image3Extension, $allowedExtenstions)):
            $error =
                "Only .jpeg, .jpg, and .png images are accepted for image 3";
        elseif (!in_array($image4Extension, $allowedExtenstions)):
            $error =
                "Only .jpeg, .jpg, and .png images are accepted for image 4";
        elseif (!in_array($image_MIME, $allowedMIME)):
            $error = "Image 1 MIME type in not accepted.";
        elseif (!in_array($image2_MIME, $allowedMIME)):
            $error = "Image 2 MIME type in not accepted.";
        elseif (!in_array($image3_MIME, $allowedMIME)):
            $error = "Image 3 MIME type in not accepted.";
        elseif (!in_array($image4_MIME, $allowedMIME)):
            $error = "Image 4 MIME type in not accepted.";
        elseif ($image_S > 3146000):
            $error = "Image 1 size is too big.";
        elseif ($image2_S > 3146000):
            $error = "Image 2 size is too big.";
        elseif ($image3_S > 3146000):
            $error = "Image 3 size is too big.";
        elseif ($image4_S > 3146000):
            $error = "Image 4 size is too big.";
        else:
            $stmt = $conn->prepare("INSERT INTO products (product_name, product_category, product_description, product_image, product_image2, product_image3, product_image4, product_price, product_special_offer, product_color)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);
    ");

            $stmt->bind_param(
                "sssssssdis",
                $name,
                $category,
                $description,
                $image_T,
                $image2_T,
                $image3_T,
                $image4_T,
                $price,
                $offer,
                $color
            );

            if ($stmt->execute()):
                $new_id = $stmt->insert_id;
                $img_name = $new_id . "." . $imageExtension;
                $img2_name = $new_id . "_2." . $image2Extension;
                $img3_name = $new_id . "_3." . $image3Extension;
                $img4_name = $new_id . "_4." . $image4Extension;

                move_uploaded_file($image_T, "../assets/imgs/$img_name");
                move_uploaded_file($image2_T, "../assets/imgs/$img2_name");
                move_uploaded_file($image3_T, "../assets/imgs/$img3_name");
                move_uploaded_file($image4_T, "../assets/imgs/$img4_name");

                $stmt2 = $conn->prepare("UPDATE products SET
                                        product_image = ?,
                                        product_image2 = ?,
                                        product_image3 = ?,
                                        product_image4 = ? WHERE product_id = ?
                                        ");

                $stmt2->bind_param(
                    "ssssi",
                    $img_name,
                    $img2_name,
                    $img3_name,
                    $img4_name,
                    $new_id
                );
                if ($stmt2->execute()):
                    header(
                        "location: products.php?message=Product added successfully."
                    );
                    exit();
                else:
                    header(
                        "location: products.php?error=Images could not be uploaded due to an unexpected error. Please procceed and add them through the products page or manually into the database&message=Product details has been added."
                    );
                    exit();
                endif;
            else:
                $error = "Unexpected error: Could not add product.";
            endif;
        endif;
    endif;
}
?>

<!--/////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<?php require "sidebar.php"; ?>


<div class="text-center my-2 py-3">
    <h1>
        Add Product
    </h1>

    <?php if (isset($error)):
        echo "<p class=\"mt-3 text-center pt-3\" style=\"color: red;\">";
        echo $error;
        echo "</p>";
    endif; ?>

    <div class="container text-center my-5 align mx-auto">
        <form id="product-form" enctype="multipart/form-data" method="post" action="">

            <div class="row g-3 align-items-center my-5">
                <div class="col-auto">
                    <label for="product-id" class="col-form-label">ID : </label>
                </div>
                <div class="col-auto">
                    <span class="form-text">
                        Auto - Increment
                    </span>
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
                        required />
                </div>
            </div>

            <div class="row g-3 align-items-center my-5">
                <div class="col-auto">
                    <label for="product-image" class="col-form-label">Image : </label>
                </div>
                <div class="col-auto">
                    <input
                        type="file"
                        class="form-control"
                        id="product-image"
                        name="image"
                        required />
                </div>
                <div class="col-auto">
                    <span class="form-text">
                        .jpeg, .jpg, and .png files only
                    </span>
                </div>
            </div>


            <div class="row g-3 align-items-center my-5">
                <div class="col-auto">
                    <label for="product-image2" class="col-form-label">Image 2 : </label>
                </div>
                <div class="col-auto">
                    <input
                        type="file"
                        class="form-control"
                        id="product-image2"
                        name="image2"
                        required />
                </div>
                <div class="col-auto">
                    <span class="form-text">
                        .jpeg, .jpg, and .png files only
                    </span>
                </div>
            </div>


            <div class="row g-3 align-items-center my-5">
                <div class="col-auto">
                    <label for="product-image3" class="col-form-label">Image 3 : </label>
                </div>
                <div class="col-auto">
                    <input
                        type="file"
                        class="form-control"
                        id="product-image3"
                        name="image3"
                        required />
                </div>
                <div class="col-auto">
                    <span class="form-text">
                        .jpeg, .jpg, and .png files only
                    </span>
                </div>
            </div>


            <div class="row g-3 align-items-center my-5">
                <div class="col-auto">
                    <label for="product-image4" class="col-form-label">Image 4 : </label>
                </div>
                <div class="col-auto">
                    <input
                        type="file"
                        class="form-control"
                        id="product-image4"
                        name="image4"
                        required />
                </div>
                <div class="col-auto">
                    <span class="form-text">
                        .jpeg, .jpg, and .png files only
                    </span>
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
                        <option value="Sofa" selected>Sofa</option>
                        <option value="Bed">Bed</option>
                        <option value="Armchair">Armchair</option>
                        <option value="Table">Table</option>
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


            <div class="row g-3 align-items-center my-5">
                <div class="col-auto">
                    <label for="product-offer" class="col-form-label">Offer : </label>
                </div>
                <div class="col-auto">
                    <input
                        type="number"
                        class="form-control"
                        id="product-offer"
                        name="offer"
                        min="0"
                        max="100"
                        value="0"
                        step="0.01"
                        required />
                </div>
                <div class="col-auto">
                    <span class="form-text">
                        %
                    </span>
                </div>
            </div>


            <div class="row g-3 align-items-center mt-5 text-center">
                <div class="col-auto">
                    <input
                        type="submit"
                        class="btn btn-primary"
                        name="add"
                        value="Add" />
                </div>
            </div>


        </form>
    </div>



</div>


<?php require "footer.php"; ?>
