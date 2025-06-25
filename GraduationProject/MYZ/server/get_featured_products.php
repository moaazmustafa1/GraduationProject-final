<?php
include("connection.php");

$stmt = $conn->prepare("SELECT * FROM products 
WHERE product_id = '2' OR 
product_id = '10' OR
product_id =  '7' OR
product_id =  '11'
LIMIT 4");

$stmt->execute();

$featured_products = $stmt->get_result();
