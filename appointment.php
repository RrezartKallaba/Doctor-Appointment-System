<?php
session_start();
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_SESSION["user"])) {
    // 
} else if (isset($_SESSION["admin"])) {
    header("Location: admin/dashboard.php");
    exit();
} else if (isset($_SESSION["doctor"])) {
    header("Location: doctor/index.php");
    exit();
}

// Validimi i appointment fillimi
require_once "validate/connect.php";
$username = $usersurname = $useremail = $userappointmentdate = $doctor_id = "";
$usernameError = $usersurnameError = $useremailError = $userappointmentdateError = $doctor_idError = "";
$error = false;

function cleanInputs($input)
{
    $data = trim($input);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["appointment"])) {
    $username = cleanInputs($_POST["username"]);
    $usersurname = cleanInputs($_POST["usersurname"]);
    $useremail = cleanInputs($_POST["useremail"]);
    $userappointmentdate = $_POST["userappointmentdate"];
    $doctor_id = explode('|', cleanInputs($_POST['doctor_id']))[0];

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
        $useremailError = "Please enter a valid email address.";
    }

    $currentDate = date("Y-m-d"); // Marrja e dates aktuale
    if (empty($userappointmentdate)) {
        $error = true;
        $userappointmentdateError = "Appointment Date is required.";
    } else if ($userappointmentdate < $currentDate) {
        $error = true;
        $userappointmentdateError = "The selected appointment date must be greater than the current date.";
    }
    if ($doctor_id == "null") {
        $error = true;
        $doctor_idError = "Please select a doctor.";
    }
    if (!$error) {
        if (isset($_SESSION["user"])) {
            $user_id = $_SESSION["user"];
            $sql = "INSERT INTO appointments (user_id, doctor_id, patient_name, appointment_date) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($connect, $sql);
            mysqli_stmt_bind_param($stmt, "iiss", $user_id, $doctor_id, $patient_name, $userappointmentdate);
            $patient_name = $username . " " . $usersurname;
        } else {
            $sql = "INSERT INTO appointments (doctor_id, patient_name, emailuser_unregistred, appointment_date) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($connect, $sql);
            mysqli_stmt_bind_param($stmt, "isss", $doctor_id, $patient_name, $useremail, $userappointmentdate);
            $patient_name = $username . " " . $usersurname;
        }

        if (mysqli_stmt_execute($stmt)) {
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'usertesttemail@gmail.com';
                $mail->Password = 'ozgrevgoayadyrgt';
                $mail->SMTPSecure = 'tls';
                $mail->addEmbeddedImage('user_img/doctor.png', 'doctor_image', 'doctor.png');
                $mail->Port = 587;

                $mail->setFrom('usertesttemail@gmail.com', 'HealthCare');
                $mail->addAddress($useremail, $username . ' ' . $usersurname);
                $mail->Subject = 'Your Appointment Details';

                // Krijimi i permbajtjes së emailit
                $emailContent = "
        <html>
        <head>
        <style>
            /* Stilizime CSS për email */
            body {
                font-family: Arial, sans-serif;
                background-color: #f5f5f5;
            }
            .email-content {
                font-family: Arial, sans-serif;
                background-color: #f5f5f5;
                padding: 20px;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
                max-width: 600px;
                margin: 0 auto;
            }
    
            .thank-you {
                color: #4285f4;
                font-size: 24px;
                text-align: center;
            }
    
            b {
                font-weight: bold;
            }
            
            .email-footer {
                margin-top: 20px;
                padding-top: 10px;
                border-top: 1px solid #ddd;
                text-align: center;
                font-size: 12px;
                color: #999;
            }
            .content{
                display:flex;
                flex-direction: row;
                margin: 0 70px;
            }
            .doctor img{
                max-width: 200px;
            }
            .client-details{
                padding: 0 30px 0 0;
            }
            .doctor{
                padding: 0 0 0 30px;
            }
            @media (max-width: 600px){
                .content{
                    margin: 0;
                }
            }
        </style>
        </head>
        <body>
        <div class='email-content'>
            <h2 class='thank-you'>Thank you for your appointment!</h2>
            <div class='content'>
                <div class='client-details'>
                    <p><i>Here are your appointment details:</i></p>
                    <p><b>First Name:</b> $username</p>
                    <p><b>Last Name:</b> $usersurname</p>
                    <p><b>Email:</b> $useremail</p>
                    <p><b>Appointment Date:</b> $userappointmentdate</p>
                    <p><b>Doctor ID:</b> $doctor_id</p>
                </div>
                <div class='doctor'>
                    <img src='cid:doctor_image' alt='Doctor Image' draggable='false'/>
                </div>
            </div>
                <div class='email-footer'>
                    <p><b>This email was sent by HealthCare</b></p>
                </div>
        </div>
        </body>
        </html>";

                $mail->isHTML(true);
                $mail->Subject = 'Your Appointment Details';
                $mail->Body = $emailContent;

                $mail->send();

                $_SESSION['appointments'] = "✅ The appointment has been created successfully and an email has been sent to you!";
                header("Location: appointment.php");
                exit();
            } catch (Exception $e) {
                $_SESSION['appointments_error'] = "❌ There was an issue sending the email.";
                echo 'Email could not be sent. Error: ', $e->getMessage();
                header("Location: appointment.php");
                exit();
            }
        } else {
            $_SESSION["appointments_error"] = "❌ Something went wrong, please try again later.";
            header("Location: appointment.php");
            exit();
        }

        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="user_interface_css/style.css">
    <link rel="icon" href="user_img/logo_ikone.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <title>Appointment</title>
    <style>
        .alert.alert-success,
        .alert.alert-danger {
            position: absolute;
            width: 100%;
            top: 85px;
            border-radius: 0;
        }
    </style>
</head>

<body>
    <header>
        <img id="img1" src="user_img/logo.png" alt="" srcset="">
        <div class="hamburger-menu" onclick="toggleMenu()">
            <button class="openbtn">&#9776;</button>
            <button class="closebtn">&times;</button>
        </div>
        <nav id="myLinks" class="menu">
            <a href="index.php#home" class="active">Home</a>
            <a href="index.php#about">About</a>
            <a href="index.php#contact">Contact</a>
            <a class="btn" href="appointment.php">Appointment</a>
            <?php if (isset($_SESSION["user"])) {
            ?>
                <a href="validate/logout.php"><svg fill="rgb(56, 152, 236)" class="logoutbtn" width="24" height="24" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 384.971 384.971" xml:space="preserve" transform="matrix(-1, 0, 0, 1, 0, 0)">
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
                    </svg></a>
            <?php } else {
            ?>
                <a class="btn" href="login/login.php">Login</a>
            <?php } ?>
        </nav>
        <script>
            function toggleMenu() {
                var menu = document.getElementById("myLinks");
                var iconHamburger = document.querySelector(".openbtn");
                var iconClose = document.querySelector(".closebtn");

                if (menu.style.display === "block") {
                    menu.style.display = "none";
                    iconHamburger.style.display = "block";
                    iconClose.style.display = "none";
                } else {
                    menu.style.display = "block";
                    iconHamburger.style.display = "none";
                    iconClose.style.display = "block";
                }
            }
            window.addEventListener("resize", () => {
                var menu = document.getElementById("myLinks");
                var iconHamburger = document.querySelector(".openbtn");
                var iconClose = document.querySelector(".closebtn");

                if (window.innerWidth > 850) {
                    menu.style.display = "flex";
                    iconHamburger.style.display = "none";
                    iconClose.style.display = "none";
                } else {
                    menu.style.display = "none";
                    iconHamburger.style.display = "block";
                    iconClose.style.display = "none";
                }
            });
        </script>
    </header>
    <section id="appointments" class="appointments">
        <div class="content">
            <?php
            if (isset($_SESSION['appointments'])) {
                echo "<div id='success-alert' class='alert alert-success'>
                        <p style='text-align:center;'>" . $_SESSION['appointments'] . "</p>
                      </div>";
                unset($_SESSION['appointments']);
            }
            ?>
            <script>
                setTimeout(function() {
                    var successAlert = document.getElementById('success-alert');
                    if (successAlert) {
                        successAlert.style.display = 'none';
                    }
                }, 5000); // 5000 milliseconds = 5 seconds
            </script>
            <?php
            if (isset($_SESSION['appointments_error'])) {
                echo "<div id='danger-alert' class='alert alert-danger'>
                        <p style='text-align:center;'>" . $_SESSION['appointments_error'] . "</p>
                      </div>";
                unset($_SESSION['appointments_error']);
            }
            ?>
            <script>
                setTimeout(function() {
                    var dangerAlert = document.getElementById('danger-alert');
                    if (dangerAlert) {
                        dangerAlert.style.display = 'none';
                    }
                }, 3000); // 3000 milliseconds = 3 seconds
            </script>
            <div class="appointments-photo">
                <img src="user_img/appointments-photo.png" loading="lazy" alt="Appointments photo">
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 style="font-size: 23px;color: black;">Book an Appointment</h4>
                </div>
                <div class="card-body">
                    <?php if (!isset($_SESSION["user"])) { ?>
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
                            <div class="row">
                                <div class="form-group">
                                    <label for="useremail" class="form-label">Email:</label>
                                    <input type="email" name="useremail" class="form-control" value="<?php if (empty($useremailError)) echo $useremail; ?>">
                                    <span class="text-danger"><?= $useremailError ?></span>
                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="userappointmentdate" class="form-label">Appoint. Date:</label>
                                    <input type="date" name="userappointmentdate" class="form-control" value="<?php echo (empty($userappointmentdateError)) ? $userappointmentdate : ''; ?>">
                                    <span class="text-danger"><?= $userappointmentdateError ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="doctor_id" class="form-label">Select Doctor:</label>
                                    <select name="doctor_id" id="doctor_id" class="form-control">
                                        <?php
                                        require_once "validate/connect.php";
                                        $doctorQuery = "SELECT * FROM doctors";
                                        $doctorResult = mysqli_query($connect, $doctorQuery);
                                        echo "<option value='null'>Please select a doctor</option>";
                                        while ($doctorRow = mysqli_fetch_assoc($doctorResult)) {
                                            $doctorValue = $doctorRow['doctor_id'] . '|' . $doctorRow['name'] . '|' . $doctorRow['specialization'];
                                            echo "<option value='{$doctorValue}'";
                                            echo ">{$doctorRow['doctor_id']} - {$doctorRow['name']}- {$doctorRow['specialization']}</option>";
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?= $doctor_idError ?></span>
                                </div>
                            </div>
                            <div class="button">
                                <button type="submit" name="appointment" class="btn">Submit</button>
                            </div>
                        </form>
                    <?php
                    } else { ?>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" autocomplete="off">
                            <div class="row">
                                <div class="form-group">
                                    <label for="username" class="form-label">First name:</label>
                                    <input type="text" name="username" class="form-control" value="<?php
                                                                                                    if (isset($_SESSION["username"])) {
                                                                                                        $first_name = explode(' ', $_SESSION["username"]);
                                                                                                        if (count($first_name) > 1) {
                                                                                                            echo $first_name[0];
                                                                                                        } else {
                                                                                                            echo $_SESSION["username"];
                                                                                                        }
                                                                                                    }
                                                                                                    ?>">
                                    <span class="text-danger"><?= $usernameError ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="usersurname" class="form-label">Last name:</label>
                                    <input type="text" name="usersurname" class="form-control" value="<?php
                                                                                                        if (isset($_SESSION["username"])) {
                                                                                                            $last_name = explode(' ', $_SESSION["username"]);
                                                                                                            if (count($last_name) > 1) {
                                                                                                                echo $last_name[1];
                                                                                                            } else {
                                                                                                                echo $_SESSION["username"];
                                                                                                            }
                                                                                                        }
                                                                                                        ?>">
                                    <span class="text-danger"><?= $usersurnameError ?></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="useremail" class="form-label">Email:</label>
                                    <input type="email" name="useremail" class="form-control" value="<?php echo $_SESSION["useremail"];  ?>">
                                    <span class="text-danger"><?= $useremailError ?></span>
                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="userappointmentdate" class="form-label">Appoint. Date:</label>
                                    <input type="date" name="userappointmentdate" class="form-control" value="<?php echo (empty($userappointmentdateError)) ? $userappointmentdate : ''; ?>">
                                    <span class="text-danger"><?= $userappointmentdateError ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="doctor_id" class="form-label">Select Doctor:</label>
                                    <select name="doctor_id" id="doctor_id" class="form-control">
                                        <?php
                                        require_once "validate/connect.php";
                                        $doctorQuery = "SELECT * FROM doctors";
                                        $doctorResult = mysqli_query($connect, $doctorQuery);
                                        echo "<option value='null'>Please select a doctor</option>";
                                        while ($doctorRow = mysqli_fetch_assoc($doctorResult)) {
                                            $doctorValue = $doctorRow['doctor_id'] . '|' . $doctorRow['name'] . '|' . $doctorRow['specialization'];
                                            echo "<option value='{$doctorValue}'";
                                            echo ">{$doctorRow['doctor_id']} - {$doctorRow['name']}- {$doctorRow['specialization']}</option>";
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?= $doctor_idError ?></span>
                                </div>
                            </div>
                            <div class="button">
                                <button type="submit" name="appointment" class="btn">Submit</button>
                            </div>
                        </form>
                    <?php
                    }
                    ?>

                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="content">
            <div class="left-content">
                <h2>Quick Links</h2>
                <a href="#home"><i class="fas fa-chevron-right"></i>Home</a>
                <a href="#about"><i class="fas fa-chevron-right"></i>About</a>
                <a href="#contact"><i class="fas fa-chevron-right"></i>Contact</a>
                <a href="appointment.php"><i class="fas fa-chevron-right"></i>Appointment</a>
                <a href="login/login.php"><i class="fas fa-chevron-right"></i>Login</a>
            </div>
            <div class="center-content">
                <h2>Services</h2>
                <a href="#"><i class="fas fa-chevron-right"></i>Emergency Care</a>
                <a href="#"><i class="fas fa-chevron-right"></i>Specialist Consultations</a>
                <a href="#"><i class="fas fa-chevron-right"></i>Surgery</a>
                <a href="#"><i class="fas fa-chevron-right"></i>Radiology and Imaging</a>
                <a href="#"><i class="fas fa-chevron-right"></i>Intensive Care</a>
            </div>
            <div class="right-content">
                <h2>Contact Info</h2>
                <a href="#"> <i class="fas fa-phone"></i>049******</a>
                <a href="#"> <i class="fas fa-envelope"></i>rrezartkallaba@gmail.com</a>
                <a href="#"> <i class="fas fa-map-marker-alt"></i>Suharekë</a>
            </div>
        </div>
        <div class="created">
            <p>©Created by <b>@rrezartkallaba</b></p>
        </div>
    </footer>
    <script src="js/main.js"></script>
</body>

</html>
<?php
mysqli_close($connect);
?>