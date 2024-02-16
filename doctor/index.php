<?php
session_start();
if (isset($_SESSION["doctor"])) {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
        <link rel="icon" href="../user_img/logo_ikone.png" type="image/x-icon">
        <title>Doctor</title>
        <link rel="stylesheet" href="doctor_css/doctor.css" />
    </head>

    <body>
        <div class="dashboard">
            <header id="myLinks" class="menu-wrap">
                <div class="user">
                    <div class="user-avatar">
                        <?php
                        if (isset($_SESSION['doctorname'])) {
                            $first_letter = substr($_SESSION['doctorname'], 0, 1);
                            echo strtoupper($first_letter);
                        }
                        ?>
                    </div>
                    <h1><?php if (isset($_SESSION["doctorname"])) {
                            echo $_SESSION["doctorname"];
                        }; ?></h1>
                </div>
                <nav>
                    <section class="manage">
                        <h2>Appointments</h2>

                        <ul>
                            <li class="active">
                                <a href="index.php" style="color: #4b84fe;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 448 512">
                                        <path style="fill: #4b84fe !important;" d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-96 55.2C54 332.9 0 401.3 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7c0-81-54-149.4-128-171.1V362c27.6 7.1 48 32.2 48 62v40c0 8.8-7.2 16-16 16H336c-8.8 0-16-7.2-16-16s7.2-16 16-16V424c0-17.7-14.3-32-32-32s-32 14.3-32 32v24c8.8 0 16 7.2 16 16s-7.2 16-16 16H256c-8.8 0-16-7.2-16-16V424c0-29.8 20.4-54.9 48-62V304.9c-6-.6-12.1-.9-18.3-.9H178.3c-6.2 0-12.3 .3-18.3 .9v65.4c23.1 6.9 40 28.3 40 53.7c0 30.9-25.1 56-56 56s-56-25.1-56-56c0-25.4 16.9-46.8 40-53.7V311.2zM144 448a24 24 0 1 0 0-48 24 24 0 1 0 0 48z" />
                                    </svg>
                                    Current Doctor
                                </a>
                            </li>
                            <li>
                                <?php if (isset($_SESSION["doctor"])) { ?>
                                    <a class="btn" href="../validate/logout.php">
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
                    <?php
                    require_once "../validate/connect.php";
                    $id = $_SESSION["doctor"];;
                    $sql_doctor_id = "SELECT doctor_id FROM doctors WHERE user_id = $id";
                    $result_doctor_id = mysqli_query($connect, $sql_doctor_id);

                    if (mysqli_num_rows($result_doctor_id) > 0) {
                        $row_doctor_id = mysqli_fetch_assoc($result_doctor_id);

                        $doctor_id = $row_doctor_id["doctor_id"];

                        $sql_doctor_info = "SELECT * FROM doctors WHERE doctor_id = $doctor_id";
                        $result_doctor_info = mysqli_query($connect, $sql_doctor_info);
                        if (mysqli_num_rows($result_doctor_info) > 0) {
                            $row = mysqli_fetch_assoc($result_doctor_info); // Merrni të dhënat e doktorit
                        } else {
                            echo "Të dhënat e doktorit nuk u gjetën.";
                        }
                    }
                    $currentDate = date("Y-m-d"); // Marrja e dates aktuale
                    $sqlAppointments = "SELECT COUNT(appointment_id) AS appointment_count FROM appointments WHERE doctor_id = $doctor_id AND appointment_date > '$currentDate'";
                    $resultAppointments = mysqli_query($connect, $sqlAppointments);
                    $rowAppointments = mysqli_fetch_assoc($resultAppointments);

                    $sqlTodayAppointments = "SELECT COUNT(appointment_id) AS today_appointment_count FROM appointments WHERE doctor_id = $doctor_id AND appointment_date = '$currentDate'";
                    $resultTodayAppointments = mysqli_query($connect, $sqlTodayAppointments);
                    $rowTodayAppointments = mysqli_fetch_assoc($resultTodayAppointments);
                    ?>
                    <section class="info-boxes">
                        <a style="text-decoration: none;" href="index.php">
                            <div class="info-box active">
                                <div class="box-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 448 512">
                                        <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-96 55.2C54 332.9 0 401.3 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7c0-81-54-149.4-128-171.1V362c27.6 7.1 48 32.2 48 62v40c0 8.8-7.2 16-16 16H336c-8.8 0-16-7.2-16-16s7.2-16 16-16V424c0-17.7-14.3-32-32-32s-32 14.3-32 32v24c8.8 0 16 7.2 16 16s-7.2 16-16 16H256c-8.8 0-16-7.2-16-16V424c0-29.8 20.4-54.9 48-62V304.9c-6-.6-12.1-.9-18.3-.9H178.3c-6.2 0-12.3 .3-18.3 .9v65.4c23.1 6.9 40 28.3 40 53.7c0 30.9-25.1 56-56 56s-56-25.1-56-56c0-25.4 16.9-46.8 40-53.7V311.2zM144 448a24 24 0 1 0 0-48 24 24 0 1 0 0 48z" />
                                    </svg>
                                </div>

                                <div class="box-content">
                                    <span class="big" style="font-size: 28px;"><?php echo $row["name"]; ?></span>
                                    Current Doctor
                                </div>
                            </div>
                        </a>
                        <a style="text-decoration: none;" href="#today_controls">
                            <div class="info-box active" style="border: none;">
                                <div class="box-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path d="M3,21c0,0.553,0.448,1,1,1h16c0.553,0,1-0.447,1-1v-1c0-3.714-2.261-6.907-5.478-8.281C16.729,10.709,17.5,9.193,17.5,7.5 C17.5,4.468,15.032,2,12,2C8.967,2,6.5,4.468,6.5,7.5c0,1.693,0.771,3.209,1.978,4.219C5.261,13.093,3,16.287,3,20V21z M8.5,7.5 C8.5,5.57,10.07,4,12,4s3.5,1.57,3.5,3.5S13.93,11,12,11S8.5,9.43,8.5,7.5z M12,13c3.859,0,7,3.141,7,7H5C5,16.141,8.14,13,12,13z" />
                                    </svg>
                                </div>

                                <div class="box-content">
                                    <span class="big"><?php echo $rowTodayAppointments["today_appointment_count"]; ?></span>
                                    Today Appointments
                                </div>
                            </div>
                        </a>
                        <a style="text-decoration: none;" href="#upcoming-controls">
                            <div class="info-box active" style="border: none;">
                                <div class="box-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 576 512">
                                        <path d="M142.4 21.9c5.6 16.8-3.5 34.9-20.2 40.5L96 71.1V192c0 53 43 96 96 96s96-43 96-96V71.1l-26.1-8.7c-16.8-5.6-25.8-23.7-20.2-40.5s23.7-25.8 40.5-20.2l26.1 8.7C334.4 19.1 352 43.5 352 71.1V192c0 77.2-54.6 141.6-127.3 156.7C231 404.6 278.4 448 336 448c61.9 0 112-50.1 112-112V265.3c-28.3-12.3-48-40.5-48-73.3c0-44.2 35.8-80 80-80s80 35.8 80 80c0 32.8-19.7 61-48 73.3V336c0 97.2-78.8 176-176 176c-92.9 0-168.9-71.9-175.5-163.1C87.2 334.2 32 269.6 32 192V71.1c0-27.5 17.6-52 43.8-60.7l26.1-8.7c16.8-5.6 34.9 3.5 40.5 20.2zM480 224a32 32 0 1 0 0-64 32 32 0 1 0 0 64z" />
                                    </svg>
                                </div>
                                <div class="box-content">
                                    <span class="big"><?php echo $rowAppointments["appointment_count"]; ?></span>
                                    Upcoming Appointments
                                </div>
                            </div>
                        </a>

                    </section>
                    <section id="today-controls" class="doctors-info" style="padding: 0 0 40px 0">
                        <div class="table-doctors">
                            <h4>Today Appointments for doctor <?php echo $row["name"]; ?></h4>
                            <table cellspacing="0" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="column-doctorId">Doctor ID</th>
                                        <th class="column-doctorName">Name</th>
                                        <th class="column-appoId">Appo. ID</th>
                                        <th class="column-pacientName">Patient Name</th>
                                        <th class="column-appointmentDate">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    require_once "../validate/connect.php";
                                    $id =   $_SESSION["doctor"];
                                    $currentTodayDate = date("Y-m-d");
                                    $sqlTodayControls = "SELECT * FROM doctors doc, appointments appo WHERE doc.doctor_id = appo.doctor_id AND appo.doctor_id = $doctor_id AND appo.appointment_date = '$currentTodayDate' ORDER BY appo.appointment_date";

                                    $resultTodayControls = mysqli_query($connect, $sqlTodayControls);

                                    if (mysqli_num_rows($resultTodayControls) > 0) {
                                        while ($rowTodayControls = mysqli_fetch_assoc($resultTodayControls)) {
                                            echo '<tr>
                                        <td class="column-tdId">' . $rowTodayControls["doctor_id"] . '</td>
                                        <td class="column-tdName">' . $rowTodayControls["name"] . '</td>
                                        <td class="column-tdAppoId">' . $rowTodayControls["appointment_id"] . '</td>
                                        <td class="column-tdPacName">' . $rowTodayControls["patient_name"] . '</td> 
                                        <td class="column-tdDate">' . $rowTodayControls["appointment_date"] . '</td>
                                        </tr>';
                                        }
                                    } else {
                                        echo '<tr style="text-align: center;"><td colspan="6" class="text-center">No result found</td></tr>';
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </section>
                    <section id="upcoming-controls" class="doctors-info">
                        <div class="table-doctors">
                            <h4>Upcoming Appointments for doctor <?php echo $row["name"]; ?></h4>
                            <table cellspacing="0" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="column-doctorId">Doctor ID</th>
                                        <th class="column-doctorName">Name</th>
                                        <th class="column-appoId">Appo. ID</th>
                                        <th class="column-pacientName">Patient Name</th>
                                        <th class="column-appointmentDate">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    require_once "../validate/connect.php";
                                    $id = $_SESSION["doctor"];
                                    $currentUpcomingDate = date("Y-m-d");
                                    $sqlUpcomingControls = "SELECT * FROM doctors doc, appointments appo WHERE doc.doctor_id = appo.doctor_id AND appo.doctor_id = $doctor_id AND appo.appointment_date > '$currentUpcomingDate' ORDER BY appo.appointment_date";

                                    $resultUpcomingControls = mysqli_query($connect, $sqlUpcomingControls);

                                    if (mysqli_num_rows($resultUpcomingControls) > 0) {
                                        while ($rowUpcomingControls = mysqli_fetch_assoc($resultUpcomingControls)) {
                                            echo '<tr>
                                        <td class="column-tdId">' . $rowUpcomingControls["doctor_id"] . '</td>
                                        <td class="column-tdName">' . $rowUpcomingControls["name"] . '</td>
                                        <td class="column-tdAppoId">' . $rowUpcomingControls["appointment_id"] . '</td>
                                        <td class="column-tdPacName">' . $rowUpcomingControls["patient_name"] . '</td> 
                                        <td class="column-tdDate">' . $rowUpcomingControls["appointment_date"] . '</td>
                                        </tr>';
                                        }
                                    } else {
                                        echo '<tr style="text-align: center;"><td colspan="6" class="text-center">No result found</td></tr>';
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </main>
        </div>
        <script src="js/main.js"></script>
    </body>

    </html>
<?php
} else if (isset($_SESSION["admin"])) {
    header("Location: ../admin/dashboard.php");
} else if (isset($_SESSION["user"])) {
    header("Location: ../index.php");
} else {
    header("Location: ../index.php");
}
mysqli_close($connect);
?>