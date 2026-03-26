<?php
session_start();
require_once '../config/database.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM contestants WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {

        if (password_verify($password, $user['password'])) {

            $_SESSION['contestant_id'] = $user['id'];
            header("Location: dashboard.php");
            exit;

        } else {
            $message = "❌ Wrong password!";
        }

    } else {
        $message = "❌ Email not found!";
    }
}
?>

<form method="POST">
<input name="email" placeholder="Email">
<input type="password" name="password" placeholder="Password">
<button>Login</button>
<p><?php echo $message; ?></p>
</form>