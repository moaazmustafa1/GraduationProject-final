<?php
if (session_status() !== PHP_SESSION_ACTIVE):
    session_start();
endif; ?>

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
