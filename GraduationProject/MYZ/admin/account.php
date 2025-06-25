<?php

session_start();

include "../server/connection.php";

if (!isset($_SESSION["logged_in2"])) {
    header("location: ../login.php");
    exit();
}
?>


<?php require "sidebar.php"; ?>

<div class="row g-3 align-items-center my-5">
    <div class="col-auto">
        <label for="product-id" class="col-form-label">Admin ID : </label>
    </div>
    <div class="col-auto">
        <span class="form-text">
            <?php echo $_SESSION["admin_id"]; ?>
        </span>
    </div>
</div>



<div class="row g-3 align-items-center my-5">
    <div class="col-auto">
        <label for="product-id" class="col-form-label">Admin Name : </label>
    </div>
    <div class="col-auto">
        <span class="form-text">
            <?php echo $_SESSION["admin_name"]; ?>
        </span>
    </div>
</div>



<div class="row g-3 align-items-center my-5">
    <div class="col-auto">
        <label for="product-id" class="col-form-label">Admin email : </label>
    </div>
    <div class="col-auto">
        <span class="form-text">
            <?php echo $_SESSION["admin_email"]; ?>
        </span>
    </div>
</div>

<?php require "footer.php"; ?>
