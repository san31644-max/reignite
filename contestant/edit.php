<?php
session_start();
require_once '../config/database.php';

$id = $_SESSION['contestant_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $bio = $_POST['bio'];

    $conn->query("UPDATE contestants SET name='$name', phone='$phone', bio='$bio' WHERE id=$id");

    header("Location: dashboard.php");
}

$user = $conn->query("SELECT * FROM contestants WHERE id=$id")->fetch_assoc();
?>

<form method="POST">
<input name="name" value="<?php echo $user['name']; ?>">
<input name="phone" value="<?php echo $user['phone']; ?>">
<textarea name="bio"><?php echo $user['bio']; ?></textarea>

<button>Update</button>
</form>