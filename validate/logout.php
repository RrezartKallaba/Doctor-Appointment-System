<?php

session_start();
unset($_SESSION["user"]);
unset($_SESSION["admin"]);
unset($_SESSION["doctor"]);
session_unset();
session_destroy();
header("Location: ../index.php");
