<?php
if (isset($_SESSION["admin"])) {
    require_once "../../validate/connect.php";
    $nameError = $specializationError = "";
    $name = $specialization = "";
    $error = false;

    $id = $_GET["id"];
    $sql = "SELECT * FROM doctors WHERE doctor_id=?";
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

    if (isset($_POST["update"])) {
        $name = cleanInputs($_POST["name"]);
        $specialization = cleanInputs($_POST["specialization"]);

        if (empty($name)) {
            $error = true;
            $nameError = "Name field cannot be empty.";
        } else if (strlen($name) < 4) {
            $error = true;
            $nameError = "Name must have at least 4 characters.";
        } else if (!preg_match("/^[a-zA-Z\s.'ëçÇ]+$/u", $name)) {
            $error = true;
            $nameError = "Name must contain only letters and spaces.";
        }


        if (empty($specialization)) {
            $error = true;
            $specializationError = "Specialization field cannot be empty.";
        } else if (strlen($specialization) < 4) {
            $error = true;
            $specializationError = "Specialization must have at least 4 characters.";
        } else if (!preg_match("/^[a-zA-Z\s.'ëçÇ]+$/u", $specialization)) {
            $error = true;
            $specializationError = "Specialization must contain only letters and spaces.";
        }
        if (!$error) {
            $sql = "UPDATE doctors SET 
                name=?, 
                specialization=?
                WHERE doctor_id=?";

            $stmt_update = mysqli_prepare($connect, $sql);
            mysqli_stmt_bind_param($stmt_update, "ssi", $name, $specialization, $id);

            if (mysqli_stmt_execute($stmt_update)) {
                $_SESSION['profile_updated'] = "✅Doctor has been updated!";
                // header("refresh: 3; url= ../dashboard.php");
                echo "<script>
                setTimeout(function() {
                    window.location.href = '../dashboard.php';
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
