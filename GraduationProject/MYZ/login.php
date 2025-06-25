<?php

session_start();

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

include "server/connection.php";

if (isset($_SESSION["logged_in"])):
    header("location: account.php");
    exit();
elseif (isset($_SESSION["logged_in2"])):
    header("location: admin/index.php");
    exit();
elseif (isset($_SESSION["TimeLocked"])):
    $timecheck = time() - $_SESSION["TimeLocked"];
    if ($timecheck > 30):
        unset($_SESSION["TimeLocked"]);
        unset($_SESSION["login_attempts"]);
    endif;
elseif (isset($_POST["login_btn"])):
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    $password = $_POST["password"];

    if (empty($email)):
        $error = "Please fill in the email field.";
    elseif (empty($password)):
        $error = "Please fill in the password field.";
    else:
        $stmt1 = $conn->prepare("SELECT * FROM users WHERE BINARY user_email = ?;
                      ");

        $stmt1->bind_param("s", $email);

        if ($stmt1->execute()):
            $stmt1->bind_result(
                $user_id,
                $user_name,
                $user_email,
                $user_hashedPassword
            );
            $stmt1->store_result();
            $stmt1->fetch();
            if (password_verify($password, $user_hashedPassword)):
                $_SESSION["user_id"] = $user_id;
                $_SESSION["user_name"] = $user_name;
                $_SESSION["user_email"] = $user_email;
                $_SESSION["logged_in"] = true;
                if (isset($_SESSION["login_attempts"])):
                    unset($_SESSION["login_attempts"]);
                endif;
                session_regenerate_id(true);

                header("location: account.php?message=Welcome!");
                exit();
            else:
                $stmt2 = $conn->prepare('SELECT * FROM admins WHERE BINARY admin_email = ?;
                                ');

                $stmt2->bind_param("s", $email);

                if ($stmt2->execute()):
                    $stmt2->bind_result(
                        $admin_id,
                        $admin_name,
                        $admin_email,
                        $admin_hashedPassword
                    );
                    $stmt2->store_result();
                    $stmt2->fetch();
                    if (password_verify($password, $admin_hashedPassword)):
                        $_SESSION["admin_id"] = $admin_id;
                        $_SESSION["admin_name"] = $admin_name;
                        $_SESSION["admin_email"] = $admin_email;
                        $_SESSION["logged_in2"] = true;
                        if (isset($_SESSION["login_attempts"])):
                            unset($_SESSION["login_attempts"]);
                        endif;
                        session_regenerate_id(true);

                        header("location: admin/index.php");
                        exit();
                    else:
                        $error = "Invalid email or password.";
                        $_SESSION["login_attempts"] += 1;
                        if (
                            $_SESSION["login_attempts"] >= 5 &&
                            !isset($_SESSION["TimeLocked"])
                        ):
                            $_SESSION["TimeLocked"] = time();
                        endif;
                    endif;
                else:
                    $error = "Something went wrong.";
                endif;
            endif;
        else:
            $error = "Something went wrong.";
        endif;
    endif;
    NoReload();
endif;
?>








<?php require "layouts/header.php"; ?>

<!-- login -->
<section class="my-5 py-5">
  <div class="container text-center mt-3 pt-5">
    <h2 class="form-weight-bold">Login</h2>
    <hr class="mx-auto" />
  </div>
  <div class="mx-auto container">
    <form id="login-form" action="login.php" method="post">
      <p style="color:red">
          <?php if (isset($_GET["error"]) || isset($error)):
              $m = $_GET["error"] ?? $error;

              $m = filter_var($m, FILTER_SANITIZE_STRING);

              echo $m;
          endif; ?>
      </p>

      <!-- If the user failed to login more than 5 times  -->
      <p style="color:red">
          <?php if (
              isset($_SESSION["login_attempts"]) &&
              $_SESSION["login_attempts"] >= 5
          ):
              echo "Please try again after 30 seconds";
          endif; ?>
      </p>

      <div class="form-group">
        <label>Email</label>
        <input
          type="text"
          class="form-control"
          id="login-email"
          name="email"
          placeholder="Email"
          <?php if (isset($email)):
              echo "value = ";
              echo "'$email'";
          endif; ?>
          required />
      </div>
      <div class="form-group">
        <label>Password</label>
        <input
          type="password"
          class="form-control"
          id="login-Password"
          name="password"
          placeholder="Password"
          required />
      </div>
      <div class="form-group">
          <?php if ($_SESSION["login_attempts"] < 5): ?>
        <input type="submit" class="btn" id="login-btn" name="login_btn" value="Login" />
        <?php endif; ?>
      </div>
      <div class="form-group">
        <a href="register.php" id="register-url" class="btn">Don't have account? Register</a>
      </div>
    </form>
  </div>
</section>

<?php require "layouts/footer.php"; ?>
