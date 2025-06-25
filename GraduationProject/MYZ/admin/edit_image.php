<?php

session_start();

include "../server/connection.php";

if (!isset($_SESSION["logged_in2"])) {
    header("location: ../login.php");
    exit();
} elseif (
    isset($_GET["product_id"]) &&
    isset($_GET["product_image"]) &&
    isset($_GET["name"])
) {
    $product_id = filter_var($_GET["product_id"], FILTER_SANITIZE_NUMBER_INT);
    $product_image = filter_var($_GET["product_image"], FILTER_SANITIZE_STRING);
    $name = filter_var($_GET["name"], FILTER_SANITIZE_STRING);
} elseif (isset($_POST["confirm"])) {
    $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $product_id = filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT);
    $product_image = filter_var(
        $_POST["product_image"],
        FILTER_SANITIZE_STRING
    );

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

    if (empty($image_T)):
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
            $img_name = $product_id . "." . $imageExtension;
            $img2_name = $product_id . "_2." . $image2Extension;
            $img3_name = $product_id . "_3." . $image3Extension;
            $img4_name = $product_id . "_4." . $image4Extension;

            move_uploaded_file($image_T, "../assets/imgs/$img_name");
            move_uploaded_file($image2_T, "../assets/imgs/$img2_name");
            move_uploaded_file($image3_T, "../assets/imgs/$img3_name");
            move_uploaded_file($image4_T, "../assets/imgs/$img4_name");

            $stmt = $conn->prepare("UPDATE products SET
                                    product_image = ?,
                                    product_image2 = ?,
                                    product_image3 = ?,
                                    product_image4 = ? WHERE product_id = ?
                                    ");

            $stmt->bind_param(
                "ssssi",
                $img_name,
                $img2_name,
                $img3_name,
                $img4_name,
                $product_id
            );

            if ($stmt->execute()):
                header(
                    "location: edit_product.php?message=Image changed successfully.&product_id=$product_id&product_image=$product_image&name=$name"
                );
                exit();
            else:
                $error = "Unexpected error: Could not edit image.";
            endif;
        endif;
    endif;
}
?>


<!--/////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<?php require "sidebar.php"; ?>


<div class="text-center my-2 py-3">
    <h1>
        Edit Image for Product # <?php echo $product_id; ?>
    </h1>

    <?php if (isset($error)):
        echo "<p class=\"mt-3 text-center pt-3\" style=\"color: red;\">";
        echo $error;
        echo "</p>";
    endif; ?>

    <div class="container text-center my-5 align mx-auto">
        <form id="product-form" enctype="multipart/form-data" method="post" action="edit_image.php">
            <div class="row g-3 align-items-center my-5">
                <div class="col-auto">
                    <label for="product-id" class="col-form-label">Image : </label>
                </div>
                <div class="col-auto">
                    <img style="height: 65px;" src="../assets/imgs/<?php echo $product_image; ?>">
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
                        accept="image/png, image/jpeg, image/jpg"
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
                        accept="image/png, image/jpeg, image/jpg"
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
                        accept="image/png, image/jpeg, image/jpg"
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
                        accept="image/png, image/jpeg, image/jpg"
                        required />
                </div>
                <div class="col-auto">
                    <span class="form-text">
                        .jpeg, .jpg, and .png files only
                    </span>
                </div>
            </div>



            <div class="col-auto">
                <input
                    type="hidden"
                    class="form-control"
                    id="product-id"
                    name="id"
                    value="<?php echo $product_id; ?>" />
            </div>



            <div class="col-auto">
                <input
                    type="hidden"
                    class="form-control"
                    id="product-id"
                    name="name"
                    value="<?php echo $name; ?>" />
            </div>

            <div class="col-auto">
                <input
                    type="hidden"
                    class="form-control"
                    id="product-id"
                    name="product_image"
                    value="<?php echo $product_image; ?>" />
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
                    <a class="btn btn-danger" href="edit_product.php?product_id=<?php echo $product_id; ?>">Cancel</a>
                </div>
            </div>
        </form>
    </div>



</div>


<?php require "footer.php"; ?>
