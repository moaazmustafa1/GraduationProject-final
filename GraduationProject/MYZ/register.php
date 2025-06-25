<?php

session_start();

include "server/connection.php";

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

if (isset($_SESSION["logged_in2"])):
    header("location: admin/index.php");
    exit();
elseif (isset($_POST["register"])):
    $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];
    $confirmpassword = $_POST["confirmPassword"];

    if (empty($name)):
        $error = "Please fill in the name field.";
    elseif (empty($email)):
        $error = "Please fill in the email field.";
    elseif (empty($password)):
        $error = "Please fill in the password field.";
    elseif (empty($confirmpassword)):
        $error = "Please fill in the confirm password field.";
    else:
        $stmt1 = $conn->prepare(
            "SELECT count(*) FROM users WHERE user_email = ? ; "
        );
        $stmt1->bind_param("s", $email);
        $stmt1->execute();
        $stmt1->bind_result($num_rows);
        $stmt1->store_result();
        $stmt1->fetch();

        if ($num_rows !== 0):
            $error = "Email already exists.";
        elseif (strlen($password) < 9):
            $error = "Password must be at least 9 characters.";
        elseif ($confirmpassword !== $password):
            $error = "Passwords do not match.";
        else:
            $password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (user_name, user_email, user_password)
                        VALUES( ? , ? , ? ); ");

            $stmt->bind_param("sss", $name, $email, $password);

            if ($stmt->execute()):
                $user_id = $stmt->insert_id;

                $_SESSION["user_id"] = $user_id;
                $_SESSION["user_email"] = $email;
                $_SESSION["user_name"] = $name;
                $_SESSION["logged_in"] = true;
                session_regenerate_id(true);
                header(
                    "location: account.php?message=Registeration Successful."
                );
                exit();
            else:
                $error = "Unexpected error: Registertion failed.";
            endif;
        endif;
    endif;
    NoReload();
endif;
?>







<?php require "layouts/header.php"; ?>

<!-- Register -->
<section class="my-5 py-5">
  <div class="container text-center mt-3 pt-5">
    <h2 class="form-weight-bold">Register</h2>
    <hr class="mx-auto" />
  </div>
  <div class="mx-auto container">
    <form id="register-form" method="post" action="">
      <p style="color:red"><?php if (isset($error)) {
          echo $error;
      } ?></p>
      <div class="form-group">
        <label>Name</label>
        <input
          type="text"
          class="form-control"
          id="register-name"
          name="name"
          placeholder="Name"
          <?php if (isset($name)):
              echo "value = ";
              echo "'$name'";
          endif; ?>
          required />
      </div>
      <div class="form-group">
        <label>Email</label>
        <input
          type="email"
          class="form-control"
          id="register-email"
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
          type="Password"
          class="form-control"
          id="register-Password"
          name="password"
          placeholder="Password"
          <?php if (isset($password)):
              echo "value = ";
              echo "'$password'";
          endif; ?>
          required />
      </div>
      <div class="form-group">
        <label>Confirm Password</label>
        <input
          type="Password"
          class="form-control"
          id="register-confirmpssword"
          name="confirmPassword"
          placeholder="Confirm Password"
          <?php if (isset($confirmpassword)):
              echo "value = ";
              echo "'$confirmpassword'";
          endif; ?>
          required />
      </div>
      <div class="form-group">
        <input
          type="submit"
          class="btn"
          id="register-btn"
          name="register"
          value="Register" />
      </div>
      <div class="form-group">
        <a id="login-url" class="btn" href="login.php">Do you have an account? Login</a>
      </div>
    </form>
  </div>
</section>

<?php require "layouts/footer.php"; ?>
