<?php
session_start();
require_once '../config/database.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $category = $_POST['category'];
    $bio = $_POST['bio'];
    $password = $_POST['password'];

    if (!empty($name) && !empty($email) && !empty($password)) {

        $check = $conn->prepare("SELECT id FROM contestants WHERE email=?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "❌ Email already exists!";
        } else {

            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $img = $_FILES['photo']['name'];
            $tmp = $_FILES['photo']['tmp_name'];

            if ($img) {
                $newName = time() . "_" . rand(1000,9999) . ".jpg";
                move_uploaded_file($tmp, "../uploads/" . $newName);

                $stmt = $conn->prepare("INSERT INTO contestants (name,email,phone,category,photo,bio,password,approved) VALUES (?,?,?,?,?,?,?,1)");
                $stmt->bind_param("sssssss",$name,$email,$phone,$category,$newName,$bio,$hashed);

                if ($stmt->execute()) {

                    $id = $stmt->insert_id;
                    $code = "RCC" . str_pad($id,3,"0",STR_PAD_LEFT);
                    $conn->query("UPDATE contestants SET contestant_code='$code' WHERE id=$id");

                    $_SESSION['contestant_id'] = $id;

                    header("Location: dashboard.php");
                    exit();
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<style>
body{
    background:linear-gradient(135deg,#020617,#0f172a);
    color:white;
    font-family:Poppins;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}
.box{
    background:rgba(255,255,255,0.05);
    padding:30px;
    border-radius:15px;
    width:350px;
}
input,select,textarea{
    width:100%;
    padding:10px;
    margin:8px 0;
    border:none;
    border-radius:8px;
}
button{
    width:100%;
    padding:12px;
    background:gold;
    border:none;
    border-radius:20px;
}
</style>
</head>

<body>

<div class="box">
<h2>🧑‍🎤 Register</h2>
<p><?php echo $message; ?></p>

<form method="POST" enctype="multipart/form-data">
<input name="name" placeholder="Full Name" required>
<input name="email" placeholder="Email" required>
<input name="phone" placeholder="Phone">
<select name="category">
<option value="MR">Mr</option>
<option value="MRS">Mrs</option>
</select>
<textarea name="bio" placeholder="Bio"></textarea>
<input type="password" name="password" placeholder="Password" required>
<input type="file" name="photo" required>
<button>Register</button>
</form>
</div>

</body>
</html>