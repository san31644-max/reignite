<?php
require_once '../includes/auth.php';
require_once '../config/database.php';

$user_id = $_SESSION['user_id'];
$contestant_id = $_POST['contestant_id'];
$category = $_POST['category'];

// CHECK IF ALREADY VOTED
$stmt = $conn->prepare("SELECT * FROM votes WHERE user_id=? AND category=?");
$stmt->bind_param("is", $user_id, $category);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "❌ You already voted in this category!";
    exit();
}

// INSERT VOTE
$stmt = $conn->prepare("INSERT INTO votes (user_id, contestant_id, category) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $user_id, $contestant_id, $category);

if ($stmt->execute()) {
    header("Location: results.php");
exit();
} else {
    echo "❌ Error!";
}
?>