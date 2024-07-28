<?php
session_start();
if (isset($_SESSION["admin"])) {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
        <link rel="icon" href="../user_img/logo_ikone.png" type="image/x-icon">
        <title>Dashboard</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link rel="stylesheet" href="admin_css/style.css" />
        <style>
            .doctors-info .table-doctors table thead .column-actionBtn {
                width: 140px !important;
            }

            .doctors-info .table-doctors table thead .column-pacientName {
                width: 300px !important;
            }

            @media (max-width: 600px) {
                .doctors-info .table-doctors table thead .column-pacientName {
                    width: 100px !important;
                }

                .doctors-info .table-doctors table thead .column-actionBtn {
                    width: 50px !important;
                }
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
                                <a href="dashboard.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 448 512">
                                        <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-96 55.2C54 332.9 0 401.3 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7c0-81-54-149.4-128-171.1V362c27.6 7.1 48 32.2 48 62v40c0 8.8-7.2 16-16 16H336c-8.8 0-16-7.2-16-16s7.2-16 16-16V424c0-17.7-14.3-32-32-32s-32 14.3-32 32v24c8.8 0 16 7.2 16 16s-7.2 16-16 16H256c-8.8 0-16-7.2-16-16V424c0-29.8 20.4-54.9 48-62V304.9c-6-.6-12.1-.9-18.3-.9H178.3c-6.2 0-12.3 .3-18.3 .9v65.4c23.1 6.9 40 28.3 40 53.7c0 30.9-25.1 56-56 56s-56-25.1-56-56c0-25.4 16.9-46.8 40-53.7V311.2zM144 448a24 24 0 1 0 0-48 24 24 0 1 0 0 48z" />
                                    </svg>
                                    Current Doctors
                                </a>
                            </li>
                            <li class="active">
                                <a href="upcomingappointments.php" style="color: #4b84fe;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 576 512">
                                        <path style="fill: #4b84fe !important;" d="M142.4 21.9c5.6 16.8-3.5 34.9-20.2 40.5L96 71.1V192c0 53 43 96 96 96s96-43 96-96V71.1l-26.1-8.7c-16.8-5.6-25.8-23.7-20.2-40.5s23.7-25.8 40.5-20.2l26.1 8.7C334.4 19.1 352 43.5 352 71.1V192c0 77.2-54.6 141.6-127.3 156.7C231 404.6 278.4 448 336 448c61.9 0 112-50.1 112-112V265.3c-28.3-12.3-48-40.5-48-73.3c0-44.2 35.8-80 80-80s80 35.8 80 80c0 32.8-19.7 61-48 73.3V336c0 97.2-78.8 176-176 176c-92.9 0-168.9-71.9-175.5-163.1C87.2 334.2 32 269.6 32 192V71.1c0-27.5 17.6-52 43.8-60.7l26.1-8.7c16.8-5.6 34.9 3.5 40.5 20.2zM480 224a32 32 0 1 0 0-64 32 32 0 1 0 0 64z" />
                                    </svg>
                                    Upcoming Appointments
                                </a>
                            </li>
                            <li>
                                <a href="pastappointments.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 576 512">
                                        <path d="M142.4 21.9c5.6 16.8-3.5 34.9-20.2 40.5L96 71.1V192c0 53 43 96 96 96s96-43 96-96V71.1l-26.1-8.7c-16.8-5.6-25.8-23.7-20.2-40.5s23.7-25.8 40.5-20.2l26.1 8.7C334.4 19.1 352 43.5 352 71.1V192c0 77.2-54.6 141.6-127.3 156.7C231 404.6 278.4 448 336 448c61.9 0 112-50.1 112-112V265.3c-28.3-12.3-48-40.5-48-73.3c0-44.2 35.8-80 80-80s80 35.8 80 80c0 32.8-19.7 61-48 73.3V336c0 97.2-78.8 176-176 176c-92.9 0-168.9-71.9-175.5-163.1C87.2 334.2 32 269.6 32 192V71.1c0-27.5 17.6-52 43.8-60.7l26.1-8.7c16.8-5.6 34.9 3.5 40.5 20.2zM480 224a32 32 0 1 0 0-64 32 32 0 1 0 0 64z" />
                                    </svg>
                                    Past Appointments
                                </a>
                            </li>
                            <li>
                                <a href="crud/add_doctor.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 640 512">
                                        <path d="M96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3zM504 312V248H440c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V136c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H552v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z" />
                                    </svg>
                                    Add Doctor
                                </a>
                            </li>
                            <li>
                                <?php if (isset($_SESSION["admin"])) { ?>
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
                <?php
                if (isset($_SESSION['appointment_deleted'])) {
                    echo "<div id='success-alert' class='alert alert-success'>
                        <p style='text-align:center;'>" . $_SESSION['appointment_deleted'] . "</p>
                      </div>";
                    unset($_SESSION['appointment_deleted']);
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
                <div class="content">
                    <?php
                    require_once "../validate/connect.php";
                    // Getting data from the database
                    $sql = "SELECT COUNT(doctor_id) AS doctor_count FROM doctors"; // alias
                    $result = mysqli_query($connect, $sql);
                    $row = mysqli_fetch_assoc($result);

                    $currentDate = date("Y-m-d"); // Marrja e dates aktuale
                    $sqlAppointments = "SELECT COUNT(appointment_id) AS appointment_count FROM appointments WHERE appointment_date > '$currentDate'";
                    $resultAppointments = mysqli_query($connect, $sqlAppointments);
                    $rowAppointments = mysqli_fetch_assoc($resultAppointments);

                    $sqlTodayAppointments = "SELECT COUNT(appointment_id) AS today_appointment_count FROM appointments WHERE appointment_date = '$currentDate'";
                    $resultTodayAppointments = mysqli_query($connect, $sqlTodayAppointments);
                    $rowTodayAppointments = mysqli_fetch_assoc($resultTodayAppointments);
                    ?>
                    <section class="info-boxes">
                        <a style="text-decoration: none;" href="dashboard.php">
                            <div class="info-box">
                                <div class="box-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 448 512">
                                        <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-96 55.2C54 332.9 0 401.3 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7c0-81-54-149.4-128-171.1V362c27.6 7.1 48 32.2 48 62v40c0 8.8-7.2 16-16 16H336c-8.8 0-16-7.2-16-16s7.2-16 16-16V424c0-17.7-14.3-32-32-32s-32 14.3-32 32v24c8.8 0 16 7.2 16 16s-7.2 16-16 16H256c-8.8 0-16-7.2-16-16V424c0-29.8 20.4-54.9 48-62V304.9c-6-.6-12.1-.9-18.3-.9H178.3c-6.2 0-12.3 .3-18.3 .9v65.4c23.1 6.9 40 28.3 40 53.7c0 30.9-25.1 56-56 56s-56-25.1-56-56c0-25.4 16.9-46.8 40-53.7V311.2zM144 448a24 24 0 1 0 0-48 24 24 0 1 0 0 48z" />
                                    </svg>
                                </div>

                                <div class="box-content">
                                    <span class="big"><?php echo $row["doctor_count"]; ?></span>
                                    Current Doctors
                                </div>
                            </div>
                        </a>
                        <a style="text-decoration: none;" href="#today-controls">
                            <div class="info-box active">
                                <div class="box-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 576 512">
                                        <path d="M142.4 21.9c5.6 16.8-3.5 34.9-20.2 40.5L96 71.1V192c0 53 43 96 96 96s96-43 96-96V71.1l-26.1-8.7c-16.8-5.6-25.8-23.7-20.2-40.5s23.7-25.8 40.5-20.2l26.1 8.7C334.4 19.1 352 43.5 352 71.1V192c0 77.2-54.6 141.6-127.3 156.7C231 404.6 278.4 448 336 448c61.9 0 112-50.1 112-112V265.3c-28.3-12.3-48-40.5-48-73.3c0-44.2 35.8-80 80-80s80 35.8 80 80c0 32.8-19.7 61-48 73.3V336c0 97.2-78.8 176-176 176c-92.9 0-168.9-71.9-175.5-163.1C87.2 334.2 32 269.6 32 192V71.1c0-27.5 17.6-52 43.8-60.7l26.1-8.7c16.8-5.6 34.9 3.5 40.5 20.2zM480 224a32 32 0 1 0 0-64 32 32 0 1 0 0 64z" />
                                    </svg>
                                </div>
                                <div class="box-content">
                                    <span class="big"><?php echo $rowTodayAppointments["today_appointment_count"]; ?></span>
                                    Today Appointments
                                </div>
                            </div>
                        </a>
                        <a style="text-decoration: none;" href="#upcoming-controls">
                            <div class="info-box active">
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
                    <section class="doctors-info" style="padding: 0 0 40px 0">
                        <div class="table-doctors">
                            <h4>All Today Appointments</h4>
                            <table cellspacing="0" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="column-appointmentId">Appo. ID</th>
                                        <th class="column-pacientName">Patient Name</th>
                                        <th class="column-doctorID">Doctor ID</th>
                                        <th class="column-appointmentDate">Date</th>
                                        <th class="column-actionBtn">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    require_once "../validate/connect.php";
                                    $currentTodayDate = date("Y-m-d");
                                    $sqlTodayControls = "SELECT * FROM appointments WHERE appointment_date = '$currentTodayDate' ORDER BY appointment_date";

                                    $resultTodayControls = mysqli_query($connect, $sqlTodayControls);

                                    if (mysqli_num_rows($resultTodayControls) > 0) {
                                        while ($rowTodayControls = mysqli_fetch_assoc($resultTodayControls)) {
                                            echo '<tr>
                                    <td class="column-tdAppointmentId">' . $rowTodayControls["appointment_id"] . '</td>
                                    <td class="column-tdPacName">' . $rowTodayControls["patient_name"] . '</td> 
                                    <td class="column-tdDocId">' . $rowTodayControls["doctor_id"] . '</td> 
                                    <td class="column-tdDate">' . $rowTodayControls["appointment_date"] . '</td>
                                    <td class="column-tdBtn">';
                                            echo '<a href="crud/update_appointments.php?id=' . $rowTodayControls["appointment_id"] . '" class="action-icons"><svg class="update-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512"><path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z"/></svg></a> ';
                                            echo '<a href="admin_validate/validateDeleteAppoMedical.php?id=' . $rowTodayControls["appointment_id"] . '" class="action-icons" onclick="return confirm(\'Are you sure you want to delete this appointment?\')"><svg class="delete-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 448 512"><path d="M170.5 51.6L151.5 80h145l-19-28.4c-1.5-2.2-4-3.6-6.7-3.6H177.1c-2.7 0-5.2 1.3-6.7 3.6zm147-26.6L354.2 80H368h48 8c13.3 0 24 10.7 24 24s-10.7 24-24 24h-8V432c0 44.2-35.8 80-80 80H112c-44.2 0-80-35.8-80-80V128H24c-13.3 0-24-10.7-24-24S10.7 80 24 80h8H80 93.8l36.7-55.1C140.9 9.4 158.4 0 177.1 0h93.7c18.7 0 36.2 9.4 46.6 24.9zM80 128V432c0 17.7 14.3 32 32 32H336c17.7 0 32-14.3 32-32V128H80zm80 64V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16z"/></svg></a>';
                                            echo '</td>
                                    </tr>';
                                        }
                                    } else {
                                        echo '<tr style="text-align: center;"><td colspan="5" class="text-center">No result found</td></tr>';
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </section>
                    <section id="upcoming-controls" class="doctors-info">
                        <?php
                        if (isset($_SESSION['appointemnt_deleted'])) {
                            echo "<div id='success-alert' class='alert alert-success'>
                        <p style='text-align:center;'>" . $_SESSION['appointemnt_deleted'] . "</p>
                      </div>";
                            unset($_SESSION['appointemnt_deleted']);
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
                        <div class="table-doctors">
                            <h4>All Upcoming Appointments</h4>
                            <table cellspacing="0" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="column-appointmentId">Appo. ID</th>
                                        <th class="column-pacientName">Patient Name</th>
                                        <th class="column-doctorID">Doctor ID</th>
                                        <th class="column-appointmentDate">Date</th>
                                        <th class="column-actionBtn">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    require_once "../validate/connect.php";
                                    $currentAppoDate = date("Y-m-d"); // Marrja e dates aktuale
                                    $sqlAppoInfo = "SELECT * FROM appointments WHERE appointment_date > '$currentAppoDate' ORDER BY appointment_date";
                                    $resultAppoInfo = mysqli_query($connect, $sqlAppoInfo);
                                    if (mysqli_num_rows($resultAppoInfo) > 0) {
                                        while ($rowAppoInfo = mysqli_fetch_assoc($resultAppoInfo)) {
                                            echo '<tr>
                                    <td class="column-tdAppointmentId">' . $rowAppoInfo["appointment_id"] . '</td>
                                    <td class="column-tdPacName">' . $rowAppoInfo["patient_name"] . '</td> 
                                    <td class="column-tdDocId">' . $rowAppoInfo["doctor_id"] . '</td> 
                                    <td class="column-tdDate">' . $rowAppoInfo["appointment_date"] . '</td>
                                    <td class="column-tdBtn">';
                                            echo '<a href="crud/update_appointments.php?id=' . $rowAppoInfo["appointment_id"] . '" class="action-icons"><svg class="update-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512"><path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z"/></svg></a> ';
                                            echo '<a href="admin_validate/validateDeleteAppoMedical.php?id=' . $rowAppoInfo["appointment_id"] . '" class="action-icons" onclick="return confirm(\'Are you sure you want to delete this appointment?\')"><svg class="delete-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 448 512"><path d="M170.5 51.6L151.5 80h145l-19-28.4c-1.5-2.2-4-3.6-6.7-3.6H177.1c-2.7 0-5.2 1.3-6.7 3.6zm147-26.6L354.2 80H368h48 8c13.3 0 24 10.7 24 24s-10.7 24-24 24h-8V432c0 44.2-35.8 80-80 80H112c-44.2 0-80-35.8-80-80V128H24c-13.3 0-24-10.7-24-24S10.7 80 24 80h8H80 93.8l36.7-55.1C140.9 9.4 158.4 0 177.1 0h93.7c18.7 0 36.2 9.4 46.6 24.9zM80 128V432c0 17.7 14.3 32 32 32H336c17.7 0 32-14.3 32-32V128H80zm80 64V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16z"/></svg></a>';
                                            echo '</td>
                                    </tr>';
                                        }
                                    } else {
                                        echo '<tr style="text-align: center;"><td colspan="5" class="text-center">No result found</td></tr>';
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
} else if (isset($_SESSION["doctor"])) {
    header("Location: ../doctor/index.php");
} else if (isset($_SESSION["user"])) {
    header("Location: ../index.php");
} else {
    header("Location: ../index.php");
}
mysqli_close($connect);
?>