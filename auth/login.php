<?php
session_start();
require_once '../config/database.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {

        // ======================
        // CHECK VOTERS (users)
        // ======================
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role'] = "voter";

                header("Location: ../user/dashboard.php");
                exit();
            }
        }

        // ======================
        // CHECK CONTESTANTS
        // ======================
        $stmt = $conn->prepare("SELECT * FROM contestants WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $contestant = $result->fetch_assoc();

            if (password_verify($password, $contestant['password'])) {

                $_SESSION['contestant_id'] = $contestant['id'];
                $_SESSION['contestant_name'] = $contestant['name'];
                $_SESSION['role'] = "contestant";

                header("Location: ../contestant/dashboard.php");
                exit();
            }
        }

        $message = "❌ Invalid email or password!";

    } else {
        $message = "❌ Please fill all fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - Reignite</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #020617, #0f172a);
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* BOX */
.login-box {
    width: 380px;
    padding: 30px;
    border-radius: 20px;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(15px);
    box-shadow: 0 0 30px rgba(250,204,21,0.3);
    animation: fadeIn 1s ease;
}

h2 {
    text-align: center;
    color: #facc15;
    margin-bottom: 20px;
}

/* INPUTS */
input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 10px;
    border: none;
    outline: none;
    background: rgba(255,255,255,0.1);
    color: white;
}

/* BUTTON */
button {
    width: 100%;
    padding: 14px;
    border: none;
    border-radius: 30px;
    background: linear-gradient(45deg, #facc15, #f97316);
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    transform: scale(1.05);
    box-shadow: 0 0 20px #facc15;
}

/* MESSAGE */
.message {
    text-align: center;
    margin-bottom: 10px;
    color: #f87171;
}

/* LINK */
.link {
    text-align: center;
    margin-top: 15px;
}
.link a {
    color: #38bdf8;
    text-decoration: none;
}

/* ANIMATION */
@keyframes fadeIn {
    from {opacity: 0; transform: translateY(20px);}
    to {opacity: 1; transform: translateY(0);}
}
</style>
</head>

<body>

<div class="login-box">
    <h2>🔐 Login</h2>

    <?php if(!empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST">

        <input type="email" name="email" placeholder="Email Address" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>

    </form>

    <div class="link">
        <p>No account? <a href="register.php">Register</a></p>
    </div>
</div>

</body>
</html>