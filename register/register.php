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
// Validimi i regjistrimit fillimi
require_once "../validate/connect.php";
$username = $usersurname = $useremail = $userpassword = "";
$usernameError = $usersurnameError = $useremailError =  $userpasswordError = "";
$error = false;


function cleanInputs($input)
{
    $data = trim($input); // removing extra spaces, tabs, newlines out of the string
    $data = strip_tags($data); // removing tags from the string
    $data = htmlspecialchars($data); // converting special characters to HTML entities, something like "<" and ">", it will be replaced by "&lt;" and "&gt"; 

    return $data;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = cleanInputs($_POST["username"]);
    $usersurname = cleanInputs($_POST["usersurname"]);
    $useremail = cleanInputs($_POST["useremail"]);
    $userpassword = cleanInputs($_POST["userpassword"]);


    if (empty($username)) {
        $error = true;
        $usernameError = "First name is required.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $username)) {
        $error = true;
        $usernameError = "First name can contain only letters.";
    }

    if (empty($usersurname)) {
        $error = true;
        $usersurnameError = "Last name is required.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $usersurname)) {
        $error = true;
        $usersurnameError = "Last name can contain only letters.";
    }

    if (empty($useremail)) {
        $error = true;
        $useremailError = "Email is required";
    } else if (!filter_var($useremail, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $useremailError = "Please enter a valid email address/";
    } else {
        $query = "SELECT email FROM users WHERE email='$useremail'";
        $result = mysqli_query($connect, $query);
        if (mysqli_num_rows($result) != 0) {
            $error = true;
            $useremailError = "Provided email is already in use.";
        }
    }

    if (empty($userpassword)) {
        $error = true;
        $userpasswordError = "Password is required.";
    } elseif (strlen($userpassword) < 8) {
        $error = true;
        // $userpasswordError = "Password must have at least 8 characters.";
    } else {
        $uppercase = preg_match("@[A-Z]@", $userpassword);
        $lowercase = preg_match("@[a-z]@", $userpassword);
        $number = preg_match("@[0-9]@", $userpassword);
        // $specialChars = preg_match("@[^\w]@", $userpassword);


        if (!$uppercase || !$lowercase || !$number) { // Kontrolloni për të gjitha kushtet
            $error = true;
            // $userpasswordError = "Password must contain at least one uppercase letter, one lowercase letter, and one number.";
        }
    }


    if (!$error) {
        $userpassword = hash("sha256", $userpassword);
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($connect, $query);
        $fullname = $username . " " . $usersurname;
        mysqli_stmt_bind_param($stmt, "sss", $fullname, $useremail, $userpassword);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION["user-registred"] = "✅ Account has been created";
            header("refresh: 3; url=../login/login.php");
        } else {
            $_SESSION["user-registred-error"] = "❌ Something went wrong, please try again later ...";
        }
        mysqli_stmt_close($stmt);
    }
}
// Validimi i regjistrimit fundi
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="register-css/register.css">
    <style>
        .row {
            display: flex;
            flex-direction: row !important;
            gap: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <?php
                if (isset($_SESSION['user-registred'])) {
                    echo "<div id='success-alert' class='alert alert-success'>
                        <p style='text-align:center;'>" . $_SESSION['user-registred'] . "</p>
                      </div>";
                    unset($_SESSION['user-registred']);
                }
                ?>
                <script>
                    setTimeout(function() {
                        var successAlert = document.getElementById('success-alert');
                        if (successAlert) {
                            successAlert.style.display = 'none';
                        }
                    }, 3000); // 3000 milliseconds = 3 seconds
                </script>
                <?php
                if (isset($_SESSION['user-registred-error'])) {
                    echo "<div id='danger-alert' class='alert alert-danger'>
                        <p style='text-align:center;'>" . $_SESSION['user-registred-error'] . "</p>
                      </div>";
                    unset($_SESSION['user-registred-error']);
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
                <h4 style="font-size: 26px;color: black;">Create a new account</h4>
            </div>
            <div class="card-body">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" autocomplete="off">
                    <div class="row">
                        <div class="form-group">
                            <label for="username" class="form-label">First name:</label>
                            <input type="text" name="username" class="form-control" value="<?php if (empty($usernameError)) echo $username; ?>">
                            <span class="text-danger"><?= $usernameError ?></span>
                        </div>
                        <div class="form-group">
                            <label for="usersurname" class="form-label">Last name:</label>
                            <input type="text" name="usersurname" class="form-control" value="<?php if (isset($usersurnameError)) echo $usersurname; ?>">
                            <span class="text-danger"><?= $usersurnameError ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="useremail" class="form-label">Email:</label>
                        <input type="email" name="useremail" class="form-control" value="<?php if (empty($useremailError)) echo $useremail; ?>">
                        <span class="text-danger"><?= $useremailError ?></span>
                    </div>
                    <div class="form-group">
                        <label for="userpassword" class="form-label">Password:</label>
                        <input type="password" name="userpassword" class="form-control">
                        <span class="text-danger"><?= $userpasswordError ?></span>
                        <br>
                        <div class="userpsw-check">
                            <p class="userpsw" id="pw_length">
                                &#10004;
                                Password must be at least 8 characters long.</p>
                            <p class="userpsw" id="pw_number">
                                &#10004;
                                Password must contain at least one number.</p>
                            <p class="userpsw" id="pw_uppercase">
                                &#10004;
                                Password must contain at least one uppercase letter.</p>
                            <p class="userpsw" id="pw_lowercase">
                                &#10004;
                                Password must contain at least one lowercase letter.</p>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var passwordField = document.querySelector('input[name="userpassword"]');
                            var pwLength = document.getElementById('pw_length');
                            var pwNumber = document.getElementById('pw_number');
                            var pwUppercase = document.getElementById('pw_uppercase');
                            var pwLowercase = document.getElementById('pw_lowercase');

                            passwordField.addEventListener('input', function() {
                                var isLengthValid = this.value.length >= 8;
                                var hasNumber = /\d/.test(this.value);
                                var hasUppercase = /[A-Z]/.test(this.value);
                                var hasLowercase = /[a-z]/.test(this.value);

                                pwLength.style.color = isLengthValid ? 'green' : 'red';
                                pwNumber.style.color = hasNumber ? 'green' : 'red';
                                pwUppercase.style.color = hasUppercase ? 'green' : 'red';
                                pwLowercase.style.color = hasLowercase ? 'green' : 'red';
                            });
                        });
                    </script>
                    <p class="login-account">Have an account?<a href="../login/login.php">Log in</a></p>
                    <div class="button">
                        <button type="submit" name="login" class="btn">Sign up</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>