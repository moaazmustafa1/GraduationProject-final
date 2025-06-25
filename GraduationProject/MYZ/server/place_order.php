<?php
session_start();

include "connection.php";

if (isset($_POST["place_order"])) {
    // 1. We need user information
    // 2. Get products from cart (from session)

    // 3. Store order information into database
    // 4. Store each order item in database

    // 5. Empty cart.

    // 6. Status alert to the user

    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $city = $_POST["city"];
    $address = $_POST["address"];
    $order_cost = $_SESSION["total"];
    $order_status = "unpaid";
    $user_id = $_SESSION["user_id"];
    $order_date = date("Y-m-d H:i:s");

    if (
        empty($name) ||
        empty($email) ||
        empty($phone) ||
        empty($city) ||
        empty($address)
    ):
        header(
            "location: ../checkout.php?error=Please fill in all the fields."
        );
        exit();
    else:
        $stmt = $conn->prepare("INSERT INTO orders(order_cost, order_status, user_id, user_phone,
    user_city, user_address, order_date)
    VALUES(?, ?, ?, ?, ?, ?, ?);
    ");

        $stmt->bind_param(
            "isiisss",
            $order_cost,
            $order_status,
            $user_id,
            $phone,
            $city,
            $address,
            $order_date
        );

        $stmt_status = $stmt->execute();

        if (!$stmt_status) {
            header(
                "location: ../account.php?error2=Unexpected error: Could not place order."
            );
            exit();
        }

        $order_id = $stmt->insert_id;

        foreach ($_SESSION["cart"] as $key => $value) {
            $product = $_SESSION["cart"][$key];
            $product_id = $product["product_id"];
            $product_name = $product["product_name"];
            $product_image = $product["product_image"];
            $product_price = $product["product_price"];
            $product_quantity = $product["product_quantity"];

            $stmt1 = $conn->prepare("INSERT INTO order_items (order_id,
        product_id,
        product_name,
        product_image,
        product_price,
        product_quantity,
        user_id,
        order_date
        ) VALUES(?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?
        );
        ");

            $stmt1->bind_param(
                "iissiiis",
                $order_id,
                $product_id,
                $product_name,
                $product_image,
                $product_price,
                $product_quantity,
                $user_id,
                $order_date
            );

            $stmt1->execute();
        }

        unset($_SESSION["cart"]);
        unset($_SESSION["total_quantities"]);

        header("location: ../account.php?message=Order placed successfully");
        exit();
    endif;
}
