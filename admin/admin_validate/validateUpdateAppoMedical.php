<?php
if (isset($_SESSION["admin"])) {
    require_once "../../validate/connect.php";

    $pacientError = $specializationError = $appointmentDateError = $doctorIdError = "";
    $pacient_name = $appointment_date = $doctor_id  = "";
    $error = false;

    $id = $_GET["id"];
    $sql = "SELECT * FROM appointments WHERE appointment_id=?";
    $stmt_select = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt_select, "i", $id);
    mysqli_stmt_execute($stmt_select);
    $result = mysqli_stmt_get_result($stmt_select);
    $row = mysqli_fetch_assoc($result);
    if (!$result) {
        die("Error executing query: " . mysqli_error($connect));
    }

    function cleanInputs($input)
    {
        $data = trim($input);
        $data = strip_tags($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    if (isset($_POST["update_appointment"])) {
        $patient_name = cleanInputs($_POST["patient_name"]);
        $appointment_date = cleanInputs($_POST["appointment_date"]);
        $doctor_id = explode('|', cleanInputs($_POST['doctor_id']))[0];

        if (empty($patient_name)) {
            $error = true;
            $pacientError = "Patient Name field cannot be empty.";
        } else if (strlen($patient_name) < 4) {
            $error = true;
            $pacientError = "Patient Name must have at least 4 characters.";
        } else if (!preg_match("/^[a-zA-Z\s.]+$/", $patient_name)) {
            $error = true;
            $pacientError = "Patient Name must contain only letters and spaces.";
        }

        if (empty($appointment_date)) {
            $error = true;
            $appointmentDateError = "Appointment Date field cannot be empty.";
        } else {
            $currentDate = date("Y-m-d");

            if ($appointment_date < $currentDate) {
                $error = true;
                $appointmentDateError = "Appointment Date cannot be earlier than the current date.";
            }
        }
        if (empty($doctor_id)) {
            $error = true;
            $doctorIdError = "Please select a doctor.";
        }

        if (!$error) {
            $sql = "UPDATE appointments SET 
                patient_name=?, 
                appointment_date=?,
                doctor_id=?
                WHERE appointment_id=?";

            $stmt_update = mysqli_prepare($connect, $sql);
            mysqli_stmt_bind_param($stmt_update, "ssii", $patient_name, $appointment_date, $doctor_id, $id);

            if (mysqli_stmt_execute($stmt_update)) {
                $_SESSION['appointment_updated'] = "✅Appointment has been updated!";
                // header("refresh: 3; url= ../upcomingappointments.php");
                echo "<script>
                            setTimeout(function() {
                                window.location.href = '../upcomingappointments.php';
                            }, 3000); // 3000 milliseconds = 3 seconds
                      </script>";
            } else {
                echo "We're sorry, but there was an error processing your request. Please try again later.";
            }
        }
    }
} else if (isset($_SESSION["doctor"])) {
    header("Location: ../../doctor/index.php");
} else if (isset($_SESSION["user"])) {
    header("Location: ../../index.php");
} else {
    header("Location: ../../index.php");
}
