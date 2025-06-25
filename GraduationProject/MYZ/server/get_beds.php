<?php
include("connection.php");

$stmt = $conn->prepare(
    "SELECT * FROM products 

WHERE product_category = 'Bed'

LIMIT 4
"
);

$stmt->execute();

$beds = $stmt->get_result();
