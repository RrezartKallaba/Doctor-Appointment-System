<?php
session_start();
if (isset($_SESSION["admin"])) {
    require_once "../../validate/connect.php";

    $usernameError = $usersurnameError = $useremailError = $userstatusError = $specializationError = $userpasswordError = "";
    $username = $usersurname = $useremail = $userstatus = $specialization = $userpassword = "";
    $error = false;

    function cleanInputs($input)
    {
        $data = trim($input);
        $data = strip_tags($data);
        $data = htmlspecialchars($data);

        return $data;
    }
    if (isset($_POST["add-doctor"])) {
        $username = cleanInputs($_POST["username"]);
        $usersurname = cleanInputs($_POST["usersurname"]);
        $specialization = cleanInputs($_POST["specialization"]);
        $userstatus = cleanInputs($_POST["userstatus"]);
        $useremail = cleanInputs($_POST["useremail"]);
        $userpassword = cleanInputs($_POST["userpassword"]);

        if (empty($username)) {
            $error = true;
            $usernameError = "Name is required.";
        } elseif (strlen($username) < 4) {
            $error = true;
            $usernameError = "Name must have at least 4 characters.";
        } else if (!preg_match("/^[a-zA-Z\s.'ëçÇ]+$/u", $username)) {
            $error = true;
            $usernameError = "Name must contain only letters and spaces.";
        }

        if (empty($usersurname)) {
            $error = true;
            $usersurnameError = "Surname is required.";
        } elseif (strlen($usersurname) < 4) {
            $error = true;
            $usersurnameError = "Surname must have at least 4 characters.";
        } else if (!preg_match("/^[a-zA-Z\s.'ëçÇ]+$/u", $usersurname)) {
            $error = true;
            $usersurnameError = "Surname must contain only letters and spaces.";
        }
        if (empty($specialization)) {
            $error = true;
            $specializationError = "Specialization is required.";
        } elseif (strlen($specialization) < 4) {
            $error = true;
            $specializationError = "Specialization must have at least 4 characters.";
        } else if (!preg_match("/^[a-zA-Z\s.'ëçÇ]+$/u", $specialization)) {
            $error = true;
            $specializationError = "Specialization must contain only letters and spaces.";
        }
        if ($userstatus == "null") {
            $error = true;
            $userstatusError = "Please select status.";
        }

        if (empty($useremail)) {
            $error = true;
            $useremailError = "Email is required.";
        } elseif (!filter_var($useremail, FILTER_VALIDATE_EMAIL)) {
            $error = true;
            $useremailError = "Invalid email format.";
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
            $userpasswordError = "Password must have at least 8 characters.";
        } else {
            $uppercase = preg_match("@[A-Z]@", $userpassword);
            $lowercase = preg_match("@[a-z]@", $userpassword);
            $number = preg_match("@[0-9]@", $userpassword);
            // $specialChars = preg_match("@[^\w]@", $userpassword);

            if (!$uppercase || !$lowercase || !$number) { // Kontrolloni për të gjitha kushtet
                $error = true;
                $userpasswordError = "Password must contain at least one uppercase letter, one lowercase letter, and one number.";
            }
        }



        if (!$error) {
            $userpassword = hash("sha256", $userpassword);
            $sql_user = "INSERT INTO users (username, email, password, status) VALUES ('$username $usersurname', '$useremail', '$userpassword', '$userstatus')";

            if (mysqli_query($connect, $sql_user)) {
                // Marrja e ID se se perdoruesit qe sapo eshte shtuar
                $user_id = mysqli_insert_id($connect);

                // Shtimi doktorit ne tabelen doctors duke përdorur ID ne e perdoruesit si foreign key
                $sql_doctor = "INSERT INTO doctors (user_id, name, specialization) VALUES ('$user_id', 'Dr. $username $usersurname', '$specialization')";

                if (mysqli_query($connect, $sql_doctor)) {
                    $_SESSION['added-doctor'] = "✅ Doctor with name $username $usersurname successfully added!";
                    echo "<script>window.location.href = '../dashboard.php'</script>";
                } else {
                    echo "Error adding doctor!";
                }
            } else {
                echo "Error adding user!";
            }
        }
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
        <link rel="icon" href="../../user_img/logo_ikone.png" type="image/x-icon">
        <title>Dashboard</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link rel="stylesheet" href="../admin_css/style.css" />
        <link rel="stylesheet" href="../admin_css/update_form.css" />
        <style>
            .card {
                max-width: 500px !important;
            }

            .row {
                display: flex;
                flex-direction: row !important;
                gap: 10px;
            }

            .content-wrap .content .container .card .card-body form .form-group {
                margin-bottom: 20px;
                width: 100%;
            }
        </style>
    </head>

    <body>
        <div class="dashboard">
            <header id="myLinks" class="menu-wrap">
                <div class="user">
                    <div class="user">
                        <div class="user-avatar">
                            <?php
                            if (isset($_SESSION['adminfullname'])) {
                                $first_letter = substr($_SESSION['adminfullname'], 0, 1);
                                echo strtoupper($first_letter);
                            }
                            ?>
                        </div>
                        <h1><?php if (isset($_SESSION["adminfullname"])) {
                                echo $_SESSION["adminfullname"];
                            }; ?></h1>
                    </div>
                </div>
                <nav>
                    <section class="manage">
                        <h2>Dashboard</h2>

                        <ul>
                            <li>
                                <a href="../dashboard.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 448 512">
                                        <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-96 55.2C54 332.9 0 401.3 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7c0-81-54-149.4-128-171.1V362c27.6 7.1 48 32.2 48 62v40c0 8.8-7.2 16-16 16H336c-8.8 0-16-7.2-16-16s7.2-16 16-16V424c0-17.7-14.3-32-32-32s-32 14.3-32 32v24c8.8 0 16 7.2 16 16s-7.2 16-16 16H256c-8.8 0-16-7.2-16-16V424c0-29.8 20.4-54.9 48-62V304.9c-6-.6-12.1-.9-18.3-.9H178.3c-6.2 0-12.3 .3-18.3 .9v65.4c23.1 6.9 40 28.3 40 53.7c0 30.9-25.1 56-56 56s-56-25.1-56-56c0-25.4 16.9-46.8 40-53.7V311.2zM144 448a24 24 0 1 0 0-48 24 24 0 1 0 0 48z" />
                                    </svg>
                                    Current Doctors
                                </a>
                            </li>
                            <li>
                                <a href="../upcomingappointments.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 576 512">
                                        <path d="M142.4 21.9c5.6 16.8-3.5 34.9-20.2 40.5L96 71.1V192c0 53 43 96 96 96s96-43 96-96V71.1l-26.1-8.7c-16.8-5.6-25.8-23.7-20.2-40.5s23.7-25.8 40.5-20.2l26.1 8.7C334.4 19.1 352 43.5 352 71.1V192c0 77.2-54.6 141.6-127.3 156.7C231 404.6 278.4 448 336 448c61.9 0 112-50.1 112-112V265.3c-28.3-12.3-48-40.5-48-73.3c0-44.2 35.8-80 80-80s80 35.8 80 80c0 32.8-19.7 61-48 73.3V336c0 97.2-78.8 176-176 176c-92.9 0-168.9-71.9-175.5-163.1C87.2 334.2 32 269.6 32 192V71.1c0-27.5 17.6-52 43.8-60.7l26.1-8.7c16.8-5.6 34.9 3.5 40.5 20.2zM480 224a32 32 0 1 0 0-64 32 32 0 1 0 0 64z" />
                                    </svg>
                                    Upcoming Appointments
                                </a>
                            </li>
                            <li>
                                <a href="../pastappointments.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 576 512">
                                        <path d="M142.4 21.9c5.6 16.8-3.5 34.9-20.2 40.5L96 71.1V192c0 53 43 96 96 96s96-43 96-96V71.1l-26.1-8.7c-16.8-5.6-25.8-23.7-20.2-40.5s23.7-25.8 40.5-20.2l26.1 8.7C334.4 19.1 352 43.5 352 71.1V192c0 77.2-54.6 141.6-127.3 156.7C231 404.6 278.4 448 336 448c61.9 0 112-50.1 112-112V265.3c-28.3-12.3-48-40.5-48-73.3c0-44.2 35.8-80 80-80s80 35.8 80 80c0 32.8-19.7 61-48 73.3V336c0 97.2-78.8 176-176 176c-92.9 0-168.9-71.9-175.5-163.1C87.2 334.2 32 269.6 32 192V71.1c0-27.5 17.6-52 43.8-60.7l26.1-8.7c16.8-5.6 34.9 3.5 40.5 20.2zM480 224a32 32 0 1 0 0-64 32 32 0 1 0 0 64z" />
                                    </svg>
                                    Past Appointments
                                </a>
                            </li>
                            <li class="active">
                                <a href="add_doctor.php" style="color: #4b84fe;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 640 512">
                                        <path style="fill: #4b84fe !important;" d="M96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3zM504 312V248H440c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V136c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H552v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z" />
                                    </svg>
                                    Add Doctor
                                </a>
                            </li>
                            <li>
                                <?php if (isset($_SESSION["admin"])) { ?>
                                    <a class="btn" href="../../validate/logout.php">
                                        <svg fill="rgb(56, 152, 236)" class="logoutbtn" width="24" height="24" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 384.971 384.971" xml:space="preserve" transform="matrix(-1, 0, 0, 1, 0, 0)">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                            <g id="SVGRepo_iconCarrier">
                                                <g>
                                                    <g id="Sign_Out">
                                                        <path d="M180.455,360.91H24.061V24.061h156.394c6.641,0,12.03-5.39,12.03-12.03s-5.39-12.03-12.03-12.03H12.03 C5.39,0.001,0,5.39,0,12.031V372.94c0,6.641,5.39,12.03,12.03,12.03h168.424c6.641,0,12.03-5.39,12.03-12.03 C192.485,366.299,187.095,360.91,180.455,360.91z"></path>
                                                        <path d="M381.481,184.088l-83.009-84.2c-4.704-4.752-12.319-4.74-17.011,0c-4.704,4.74-4.704,12.439,0,17.179l62.558,63.46H96.279 c-6.641,0-12.03,5.438-12.03,12.151c0,6.713,5.39,12.151,12.03,12.151h247.74l-62.558,63.46c-4.704,4.752-4.704,12.439,0,17.179 c4.704,4.752,12.319,4.752,17.011,0l82.997-84.2C386.113,196.588,386.161,188.756,381.481,184.088z"></path>
                                                    </g>
                                                    <g> </g>
                                                    <g> </g>
                                                    <g> </g>
                                                    <g> </g>
                                                    <g> </g>
                                                    <g> </g>
                                                </g>
                                            </g>
                                        </svg>
                                        Logout</a>
                                <?php } ?>
                            </li>
                        </ul>
                    </section>
                </nav>
            </header>
            <div class="hamburger-menu" onclick="toggleMenu()">
                <button class="openbtn">&#9776;</button>
                <button class="closebtn">&times;</button>
            </div>
            <main class="content-wrap">
                <div class="content">
                    <div class="container">
                        <div class="card">
                            <div class="card-header">
                                <h4 style="font-size: 23px;color: black;">Add Doctor</h4>
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Name:</label>
                                            <input type="text" name="username" class="form-control" value="<?php if (empty($usernameError)) echo $username; ?>">
                                            <span class="text-danger"><?= $usernameError ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="specialization" class="form-label">Surname:</label>
                                            <input type="text" name="usersurname" class="form-control" value="<?php if (empty($usersurnameError)) echo $usersurname; ?>">
                                            <span class="text-danger"><?= $usersurnameError ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="specialization" class="form-label">Specilization:</label>
                                            <input type="text" name="specialization" class="form-control" value="<?php if (empty($specializationError)) echo $specialization; ?>">
                                            <span class="text-danger"><?= $specializationError ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="userstatus" class="form-label">Status:</label>
                                            <select name="userstatus" class="form-control">
                                                <option value='null'>Please select status</option>;
                                                <option value='doctor' <?php if (!empty($userstatus) && $userstatus == 'doctor') echo 'selected'; ?>>Doctor</option>
                                            </select>
                                            <span class="text-danger"><?= $userstatusError ?></span>
                                        </div>
                                    </div>
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
                                    <div class="buttons">
                                        <a href="../dashboard.php" class="btn btn-outline-info width-btn-2">Back</a>
                                        <button type="submit" name="add-doctor" class="btn btn-primary width-btn-2">Add Doctor</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <script src="../js/main.js"></script>
    </body>

    </html>
<?php
} else if (isset($_SESSION["doctor"])) {
    header("Location: ../../doctor/index.php");
} else if (isset($_SESSION["user"])) {
    header("Location: ../../index.php");
} else {
    header("Location: ../../index.php");
}
mysqli_close($connect);
?>