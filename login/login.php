<?php
session_start();
if (isset($_SESSION["admin"])) {
    header("Location: ../admin/dashboard.php");
}
if (isset($_SESSION["doctor"])) {
    header("Location: ../doctor/index.php");
}
if (isset($_SESSION["user"])) {
    header("Location: ../index.php");
}

// Validimi i loginit fillimi
require_once "../validate/connect.php";
$useremailError = $userpasswordError = "";
$useremail = $userpassword = "";
$error = false;


function cleanInputs($input)
{
    $data = trim($input);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);

    return $data;
}
if (isset($_POST["login"])) {
    $useremail = cleanInputs($_POST["useremail"]);
    $userpassword = cleanInputs($_POST["userpassword"]);

    if (empty($useremail)) {
        $error = true;
        $useremailError = "Email is required.";
    } elseif (!filter_var($useremail, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $useremailError = "Please enter a valid email address.";
    }

    if (empty($userpassword)) {
        $error = true;
        $userpasswordError = "Password is required.";
    }
    if (!$error) {
        $userpassword = hash("sha256", $userpassword);

        $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $useremail, $userpassword);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            if ($row["is_banned"] === 'Yes') {
                $_SESSION["banned_user"] = "❌ You are banned from this site.";
            } else {
                if ($row["status"] == "user") {
                    $_SESSION["user"] = $row["user_id"];
                    $_SESSION["useremail"] = $row["email"];
                    $_SESSION["username"] = $row["username"];
                    $_SESSION["status"] = $row["status"];


                    $user_id = $_SESSION["user"];
                    $user_email = $_SESSION["useremail"];
                    $status = $_SESSION["status"];
                    $login_time = date('H:i:s');
                    $login_date = date('Y-m-d');

                    $sql_insert01 = "INSERT INTO time_datelogin (user_id, user_email, status, time, date) VALUES ('$user_id', '$user_email', '$status', '$login_time', '$login_date')";
                    mysqli_query($connect, $sql_insert01);


                    header("Location: ../index.php");
                } else if ($row["status"] == "doctor") {
                    $_SESSION["doctor"] = $row["user_id"];
                    $_SESSION["doctoremail"] = $row["email"];
                    $_SESSION["doctorname"] = $row["username"];
                    $_SESSION["status"] = $row["status"];

                    $doctor_id = $_SESSION["doctor"];
                    $doctor_email = $_SESSION["doctoremail"];
                    $status = $_SESSION["status"];
                    $login_time = date('H:i:s');
                    $login_date = date('Y-m-d');

                    $sql_insert01 = "INSERT INTO time_datelogin (user_id, user_email, status, time, date) VALUES ('$doctor_id', '$doctor_email', '$status', '$login_time', '$login_date')";
                    mysqli_query($connect, $sql_insert01);


                    header("Location: ../doctor/index.php");
                } else if ($row["status"] == "admin") {
                    $_SESSION["admin"] = $row["user_id"];
                    $_SESSION["adminemail"] = $row["email"];
                    $_SESSION["adminfullname"] = $row["username"];
                    $_SESSION["status"] = $row["status"];

                    $admin_id = $_SESSION["admin"];
                    $admin_email = $_SESSION["adminemail"];
                    $admin_fullname = $_SESSION["adminfullname"];
                    $status = $_SESSION["status"];
                    $login_time = date('H:i:s');
                    $login_date = date('Y-m-d');

                    $sql_insert01 = "INSERT INTO time_datelogin (user_id, user_email, status, time, date) VALUES ('$admin_id', '$admin_email', '$status', '$login_time', '$login_date')";
                    mysqli_query($connect, $sql_insert01);


                    header("Location: ../admin/dashboard.php");
                }
            }
        } else {
            $_SESSION["wronglogin"] = "❌ Wrong credentials, please try again ...";
        }
    }
}
// Validimi i loginit fundi

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login-css/login.css" />
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <?php
                if (isset($_SESSION['wronglogin'])) {
                    echo "<div id='danger-alert' class='alert alert-danger'>
                        <p style='text-align:center;'>" . $_SESSION['wronglogin'] . "</p>
                      </div>";
                    unset($_SESSION['wronglogin']);
                }
                if (isset($_SESSION['banned_user'])) {
                    echo "<div id='danger-alert' class='alert alert-danger'>
                        <p style='text-align:center;'>" . $_SESSION['banned_user'] . "</p>
                      </div>";
                    unset($_SESSION['banned_user']);
                }
                ?>
                <script>
                    setTimeout(function() {
                        var dangerAlert = document.getElementById('danger-alert');
                        if (dangerAlert) {
                            dangerAlert.style.display = 'none';
                        }
                    }, 10000); // 10000 milliseconds = 10 seconds
                </script>
                <h4 style="font-size: 26px;color: black;">Login</h4>
            </div>
            <div class="card-body">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" autocomplete="off">
                    <div class="form-group">
                        <label for="useremail" class="form-label">Email:</label>
                        <input type="email" name="useremail" class="form-control" value="<?php if (empty($useremailError)) echo $useremail; ?>">
                        <span class="text-danger"><?= $useremailError ?></span>
                    </div>
                    <div class="form-group">
                        <label for="userpassword" class="form-label">Password:</label>
                        <input type="password" id="myInput" name="userpassword" class="form-control">
                        <label><input type="checkbox" onclick="myFunction()"> Show password</label>
                        <script>
                            function myFunction() {
                                var x = document.getElementById("myInput");
                                if (x.type === "password") {
                                    x.type = "text";
                                } else {
                                    x.type = "password";
                                }
                            }
                        </script>
                        <span class="text-danger"><?= $userpasswordError ?></span>
                    </div>
                    <p class="register-account">Don't have an account?<a href="../register/register.php"> Sign up</a>
                    </p>
                    <p class="register-account-no-padd">
                        <!-- <a href="../forgot/forgot_password.php">Forgot Password</a> -->
                    </p>
                    <div class="button">
                        <button type="submit" name="login" class="btn">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>