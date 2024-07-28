<?php
session_start();
if (isset($_SESSION["admin"])) {
    require_once "../../validate/connect.php";
    $id = $_GET["id"];
    $sql = "SELECT * FROM appointments WHERE appointment_id = ?";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $delete = "DELETE FROM appointments WHERE appointment_id = ?";
        $stmt_delete = mysqli_prepare($connect, $delete);
        mysqli_stmt_bind_param($stmt_delete, "i", $id);
        if (mysqli_stmt_execute($stmt_delete)) {
            $_SESSION['appointment_deleted'] = "✅Appointment with id $id has been deleted!";
            header("Location: ../upcomingappointments.php");
        } else {
            echo "We're sorry, but there was an error processing your request. Please try again later.";
        }
    } else {
        echo "Record not found";
    }
} else if (isset($_SESSION["doctor"])) {
    header("Location: ../../doctor/index.php");
} else if (isset($_SESSION["user"])) {
    header("Location: ../../index.php");
} else {
    header("Location: ../../index.php");
}
