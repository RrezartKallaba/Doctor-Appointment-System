<?php
session_start();
if (isset($_SESSION["user"])) {
    // 
} else if (isset($_SESSION["admin"])) {
    header("Location: admin/dashboard.php");
} else if (isset($_SESSION["doctor"])) {
    header("Location: doctor/index.php");
}

// Validimi i kontaktit fillimi
require_once "validate/connect.php";
$username = $usersurname = $useremail = $usertextarea =  "";
$usernameError = $usersurnameError = $useremailError =  $usertextareaError  = "";
$error = false;

function cleanInputs($input)
{
    $data = trim($input);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return $data;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["contactform"])) {
    $username = cleanInputs($_POST["username"]);
    $usersurname = cleanInputs($_POST["usersurname"]);
    $useremail = cleanInputs($_POST["useremail"]);
    $usertextarea = cleanInputs($_POST["usertextarea"]);

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

    if (empty($usertextarea)) {
        $error = true;
        $usertextareaError = "Message is required.";
    }

    if (!$error) {
        $sql = "INSERT INTO contactform (first_name, last_name, email, message) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $username, $usersurname, $useremail, $usertextarea);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION["contact_form"] = "✅Contact form submitted successfully.";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION["contact_formerror"] = "❌ Something went wrong, please try again later.";
            header("Location: index.php");
            exit();
        }
    }
}
// Validimi i kontaktit fundi

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="user_interface_css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="icon" href="user_img/logo_ikone.png" type="image/x-icon">
    <title>Home</title>
</head>

<body>
    <header>
        <img id="img1" src="user_img/logo.png" alt="" srcset="">
        <div class="hamburger-menu" onclick="toggleMenu()">
            <button class="openbtn">&#9776;</button>
            <button class="closebtn">&times;</button>
        </div>
        <nav id="myLinks" class="menu">
            <a href="#home" class="active" onclick="closeMenu()">Home</a>
            <a href="#about" onclick="closeMenu()">About</a>
            <a href="#contact" onclick="closeMenu()">Contact</a>
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
            // Mbyllja menyse pas klikimit te ndonje faqe
            function closeMenu() {
                var menu = document.getElementById("myLinks");
                var iconHamburger = document.querySelector(".openbtn");
                var iconClose = document.querySelector(".closebtn");

                if (window.innerWidth <= 850) {
                    menu.style.display = "none";
                    iconHamburger.style.display = "block";
                    iconClose.style.display = "none";
                }
            }
        </script>
    </header>
    <section id="home" class="home">
        <div class="content">
            <h1>Welcome to Health Care Solutions</h1>
            <p>Providing top-notch medical services for your well-being</p>
            <a href="appointment.php"><button style="cursor: pointer;">Appointment</button></a>
        </div>
    </section>
    <section id="about" class="about">
        <h2>About</h2>
        <div class="content">
            <div class="bestdoctor-info">
                <h3>Connect with Our Skilled Medical Professionals</h3>
                <p>Experience the difference with Diagnosy as we strive to provide effective medical solutions tailored to your needs. Our team of seasoned healthcare providers is dedicated to ensuring your well-being and delivering exceptional care.</p>
                <p>With a focus on collaboration and patient-centered care, we aim to empower you with the knowledge and support necessary to lead a healthy and fulfilling life. Trust Diagnosy for comprehensive medical services and personalized attention.</p>
            </div>
            <div class="bestdoctor-photo">
                <img src="user_img/doctor.png" loading="lazy" alt="Best Doctor" srcset="">
            </div>
        </div>
        <h3>OUR DOCTORS</h3>
        <div class="content" style="padding: 40px 0;">
            <div class="doctor-card">
                <div class="img">
                    <img src="user_img/ilirgjoni.jpg" loading="lazy" alt="Dr. Ilir Gjoni" class="doctor-img" />
                </div>
                <div class="padd">
                    <h4>Dr. Ilir Gjoni</h4>
                    <hr style="border: 1px solid #dfdfdf;">
                    <p>Kardiologji</p>
                </div>
            </div>
            <div class="doctor-card">
                <div class="img">
                    <img src="user_img/anisaleka.jpg" loading="lazy" alt="Dr. Anisa Leka" class="doctor-img" />
                </div>
                <div class="padd">
                    <h4>Dr. Anisa Leka</h4>
                    <hr style="border: 1px solid #dfdfdf;">
                    <p>Pulmologji</p>
                </div>
            </div>
            <div class="doctor-card">
                <div class="img">
                    <img src="user_img/fatmirmaloku.jpg" loading="lazy" alt="Dr. Fatmir Maloku" class="doctor-img" />
                </div>
                <div class="padd">
                    <h4>Dr. Fatmir Maloku</h4>
                    <hr style="border: 1px solid #dfdfdf;">
                    <p>Neurologji</p>
                </div>
            </div>
        </div>
    </section>
    <section id="contact" class="contact">

        <h2>Contact</h2>
        <div class="content">
            <div class="contact-photo">
                <img src="user_img/contact.gif" loading="lazy" alt="Contact Us Image" />
            </div>
            <div class="card">
                <div class="card-header"><?php
                                            if (isset($_SESSION['contact_form'])) {
                                                echo "<div id='success-alert' class='alert alert-success'>
                        <p style='text-align:center;'>" . $_SESSION['contact_form'] . "</p>
                      </div>";
                                                unset($_SESSION['contact_form']);
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
                    if (isset($_SESSION['contact_formerror'])) {
                        echo "<div id='danger-alert' class='alert alert-danger'>
                        <p style='text-align:center;'>" . $_SESSION['contact_formerror'] . "</p>
                      </div>";
                        unset($_SESSION['contact_formerror']);
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
                    <h4>Contact Us</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="#contact" autocomplete="off">
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
                            <label for="usertextarea" class="form-label">Message:</label>
                            <textarea rows="5" cols="10" maxlength="250" name="usertextarea" class="form-control text-area"><?php if (empty($usertextareaError)) echo $usertextarea; ?></textarea>
                            <span class="text-danger"><?= $usertextareaError ?></span>
                        </div>
                        <div class="button">
                            <button type="submit" name="contactform" class="btn">Submit</button>
                        </div>
                    </form>
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