<?php
session_start();
if (isset($_SESSION["admin"])) {
    require_once "../../validate/connect.php";
    session_start();
    $id = $_GET["id"];
    $sql = "SELECT * FROM doctors WHERE doctor_id = $id";
    $result = mysqli_query($connect, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $delete = "DELETE FROM doctors WHERE doctor_id = $id";
        if (mysqli_query($connect, $delete)) {
            $_SESSION['profile_deleted'] = "✅Doctor with id $id has been deleted!";
            header("Location: ../dashboard.php");
        } else {
            echo "Error: " . mysqli_error($connect);
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
