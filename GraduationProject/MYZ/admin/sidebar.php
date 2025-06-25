<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYZ - Admin</title>
    <link rel="stylesheet" href="font-awesome/css/all.min.css" />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="css/style.css">

    <style>
        .center {
            text-align: center;
        }

        .pagination {
            display: inline-block;
        }

        .pagination a {
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color .3s;
            border: 1px solid #ddd;
            margin: 0 4px;
        }

        .pagination a.active {
            background-color: #0e2238;
            color: white;
            border: 1px solid #0e2238;
        }

        .pagination a:hover:not(.active) {
            background-color: rgb(28, 72, 120);
            color: white;
        }

        aside {
            font-size: 40px;
            line-height: 50px;
        }
        .icon1.fa-solid{
            width: 50px;
            text-align: center;
        }

        a.sidebar-link{
            padding: 0rem 0rem !important;
            border-left: 6px solid transparent !important;
        }

        .toggle-btn{
            padding: 0px 6px 0px 6px !important;
        }
    </style>

</head>

<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="icon1 fa-solid fa-grip-lines"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="index.php">MYZ-Admin</a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="index.php" class="sidebar-link">
                        <i class="icon1 fa-solid fa-house"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="orders.php" class="sidebar-link">
                        <i class="icon1 fa-solid fa-receipt"></i>
                        <span>Orders</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="products.php" class="sidebar-link">
                        <i class="icon1 fa-solid fa-boxes-stacked"></i>
                        <span>Products</span>
                    </a>
                </li>

                <!-- <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="icon1 fa-solid fa-users"></i>
                        <span>Customers</span>
                    </a>
                </li> -->

                <li class="sidebar-item">
                    <a href="add_product.php" class="sidebar-link">
                        <i class="icon1 fa-solid fa-boxes-packing"></i>
                        <span>Add Products</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="account.php" class="sidebar-link">
                        <i class="icon1 fa-solid fa-user"></i>
                        <span>Account</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a href="index.php?logout=1" class="sidebar-link">
                    <i class="icon1 fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>
        <div class="main p-3">
