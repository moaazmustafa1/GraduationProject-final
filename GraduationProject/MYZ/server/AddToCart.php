<?php
if (session_status() !== PHP_SESSION_ACTIVE):
    session_start();
endif;

function Total()
{
    $total = 0; // Total price of cart
    $total_quantities = 0;
    foreach ($_SESSION["cart"] as $key => $value) {
        $subtotal = $value["product_quantity"] * $value["product_price"];
        $total += $subtotal;
        $total_quantities += $value["product_quantity"];
    }
    $_SESSION["total"] = $total;
    $_SESSION["total_quantities"] = $total_quantities;
}

function NoReload()
{
    ?>
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
    <?php
}

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

if (isset($_POST["add-to-cart"])) {
    if (isset($_SESSION["cart"])) {
        //This checks whether the user has added an item before or not into the cart

        $products_id_array = array_column($_SESSION["cart"], "product_id"); // Creates an array from already added products

        if (!in_array($_POST["product_id"], $products_id_array)) {
            // Checks if the product the user wants to add is in the cart or not.

            $product_id = $_POST["product_id"];
            $product_name = $_POST["product_name"];
            $product_price = $_POST["product_price"];
            $product_image = $_POST["product_image"];
            $product_quantity = $_POST["product_quantity"];

            $product_array = [
                "product_id" => $product_id,
                "product_name" => $product_name,
                "product_price" => $product_price,
                "product_image" => $product_image,
                "product_quantity" => $product_quantity,
            ];

            $_SESSION["cart"][$product_id] = $product_array;
        } else {
            $product_id = $_POST["product_id"];
            $product_name = $_POST["product_name"];
            $product_price = $_POST["product_price"];
            $product_image = $_POST["product_image"];
            $product_quantity = $_POST["product_quantity"];

            $_SESSION["cart"][$product_id]["product_quantity"] +=
                $_POST["product_quantity"];
        }
    } else {
        $product_id = $_POST["product_id"];
        $product_name = $_POST["product_name"];
        $product_price = $_POST["product_price"];
        $product_image = $_POST["product_image"];
        $product_quantity = $_POST["product_quantity"];

        $product_array = [
            "product_id" => $product_id,
            "product_name" => $product_name,
            "product_price" => $product_price,
            "product_image" => $product_image,
            "product_quantity" => $product_quantity,
        ];

        $_SESSION["cart"][$product_id] = $product_array;
    }
    Total();
    NoReload();
}
?>
