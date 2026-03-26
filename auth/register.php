<?php
session_start();
require_once '../config/database.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $batch = $_POST['batch'];
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($name) && !empty($batch) && !empty($email) && !empty($password)) {

        // CHECK EMAIL EXISTS
        $check = $conn->prepare("SELECT id FROM users WHERE email=?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "❌ Email already registered!";
        } else {

            // HASH PASSWORD
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // INSERT USER
            $stmt = $conn->prepare("INSERT INTO users (name, batch, email, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $batch, $email, $hashed);

            if ($stmt->execute()) {

                // AUTO LOGIN
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['user_name'] = $name;

                // REDIRECT TO DASHBOARD
                header("Location: ../user/dashboard.php");
                exit();

            } else {
                $message = "❌ Registration failed!";
            }
        }

    } else {
        $message = "❌ All fields required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register - Reignite</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">

<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #020617, #0f172a);
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* BOX */
.container {
    width: 400px;
    padding: 30px;
    border-radius: 20px;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(15px);
    box-shadow: 0 0 40px rgba(250,204,21,0.3);
}

/* TITLE */
h2 {
    text-align: center;
    color: #facc15;
}

/* INPUTS */
input, select {
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
}

button:hover {
    transform: scale(1.05);
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
    margin-top: 10px;
}
.link a {
    color: #38bdf8;
    text-decoration: none;
}
</style>
</head>

<body>

<div class="container">

<h2>🎓 Voter Registration</h2>

<div class="message"><?php echo $message; ?></div>

<form method="POST">

<input type="text" name="name" placeholder="Full Name" required>

<select name="batch" required>
    <option value="">Select Batch</option>
    <option value="2022 OL">🎓 2022 O/L Batch</option>
    <option value="2025 AL">🎓 2025 A/L Batch</option>
</select>

<input type="email" name="email" placeholder="Email Address" required>

<input type="password" name="password" placeholder="Password" required>

<button type="submit">🚀 Register & Vote</button>

</form>

<div class="link">
Already have an account? <a href="login.php">Login</a>
</div>

</div>

</body>
</html>