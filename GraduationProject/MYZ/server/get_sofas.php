<?php
include("connection.php");

$stmt = $conn->prepare(
    "SELECT * FROM products 

WHERE product_category = 'Sofa'

LIMIT 4
"
);

$stmt->execute();

$sofas_products = $stmt->get_result();
