<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['contestant_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['contestant_id'];

$result = $conn->query("SELECT * FROM contestants WHERE id=$id");
$user = $result->fetch_assoc();
?>

<h2>Welcome <?php echo $user['name']; ?></h2>

<img src="../uploads/<?php echo $user['photo']; ?>" width="150">

<p>Email: <?php echo $user['email']; ?></p>
<p>Phone: <?php echo $user['phone']; ?></p>
<p>Category: <?php echo $user['category']; ?></p>
<p>Bio: <?php echo $user['bio']; ?></p>

<a href="edit.php">Edit Profile</a>
<a href="logout.php">Logout</a>