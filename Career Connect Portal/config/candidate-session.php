<?php

session_start();

if (!isset($_SESSION['candidate_id'])) {
    header("Location: ../auth/candidate-login.php");
    exit();
}
?>