<?php

session_start();

if (isset($_SESSION["logged_in"])) {
    header("location: ../account.php");
    exit();
} elseif (isset($_SESSION["logged_in2"])) {
    header("location: index.php");
    exit();
} else {
    header("location: ../login.php");
    exit();
}

// require "../server/connection.php";

// if (isset($_SESSION["logged_in2"])) {
//     // logged_in2 refers to admin logged in
//     header("location: index.php");
//     exit();
// } elseif (isset($_POST["login_btn"])) {
//     $email = $_POST["email"];
//     $password = $_POST["password"];

//     $stmt = $conn->prepare('SELECT * FROM admins WHERE BINARY admin_email = ? AND BINARY admin_password = ?;
//                         ');

//     $stmt->bind_param("ss", $email, $password);

//     if ($stmt->execute()) {
//         $stmt->bind_result(
//             $admin_id,
//             $admin_name,
//             $admin_email,
//             $admin_password
//         );
//         $stmt->store_result();
//         if ($stmt->num_rows() === 1) {
//             $stmt->fetch();

//             $_SESSION["admin_id"] = $admin_id;
//             $_SESSION["admin_name"] = $admin_name;
//             $_SESSION["admin_email"] = $admin_email;
//             $_SESSION["logged_in2"] = true;

//             header("location: index.php");
//         } else {
//             $error = "Invalid email or password.";
//         }
//     } else {
//         // header('location: login.php?error=Something went wrong.');
//         $error = "Something went wrong.";
//     }
// }
?>



<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/style_login.css">
</head>

<body>
    <div class="login-container">
        <div class="login-form">
            <h2>Admin Login</h2>
            <form id="loginForm" method="post" action="login.php">
                <p style="color:red"><-?php if (isset($error)) {
                 echo $error;
                } ?></p>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="login_btn" class="btn">Login</button>
            </form>
        </div>
    </div>

</body>

</html> -->
