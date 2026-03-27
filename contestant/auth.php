<?php
session_start();

if (!isset($_SESSION['contestant_id'])) {
    header("Location: login.php");
    exit();
}
?>