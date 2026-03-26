<?php
require_once '../config/database.php';

$message = ""; // ✅ Fix warning

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $category = $_POST['category'];
    $bio = $_POST['bio'];

    $imageName = $_FILES['photo']['name'];
    $tmpName = $_FILES['photo']['tmp_name'];
    $folder = "../uploads/";

    if (!empty($imageName)) {

        $ext = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png'];

        if (in_array($ext, $allowed)) {

            $newName = time() . "_" . rand(1000,9999) . "." . $ext;
            move_uploaded_file($tmpName, $folder.$newName);

            $stmt = $conn->prepare("INSERT INTO contestants (name,email,phone,category,photo,bio,approved) VALUES (?,?,?,?,?,?,1)");
            $stmt->bind_param("ssssss",$name,$email,$phone,$category,$newName,$bio);

            if ($stmt->execute()) {
                $message = "✅ Registered successfully!";
            } else {
                $message = "❌ Error saving data!";
            }

        } else {
            $message = "❌ Only JPG, JPEG, PNG allowed!";
        }

    } else {
        $message = "❌ Please upload an image!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register Contestant</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: radial-gradient(circle at top, #0f172a, #020617);
    color: white;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* FORM */
.container {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(20px);
    padding: 40px;
    border-radius: 20px;
    width: 360px;
    text-align: center;
    box-shadow: 0 0 40px rgba(250,204,21,0.2);
    animation: fadeIn 1s ease;
}

h2 {
    color: #facc15;
    margin-bottom: 10px;
}

.message {
    font-size: 14px;
    margin-bottom: 10px;
}

/* INPUTS */
input, select, textarea {
    width: 100%;
    margin: 10px 0;
    padding: 12px;
    border-radius: 10px;
    border: none;
    outline: none;
    background: rgba(255,255,255,0.08);
    color: white;
}

input:focus, select:focus, textarea:focus {
    box-shadow: 0 0 10px #38bdf8;
}

/* FILE */
.file-box {
    border: 2px dashed rgba(255,255,255,0.3);
    padding: 15px;
    border-radius: 10px;
    cursor: pointer;
    margin: 10px 0;
}

.file-box:hover {
    border-color: #facc15;
}

.file-box input {
    display: none;
}

/* BUTTON */
button {
    width: 100%;
    padding: 12px;
    border-radius: 25px;
    border: none;
    background: linear-gradient(45deg, #facc15, #f97316);
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    transform: scale(1.05);
}

/* ANIMATION */
@keyframes fadeIn {
    from {opacity:0; transform:translateY(20px);}
    to {opacity:1; transform:translateY(0);}
}
</style>
</head>

<body>

<div class="container">

<h2>🔥 Join Reignite</h2>

<p class="message"><?php echo $message ?? ''; ?></p>

<form method="POST" enctype="multipart/form-data">

<input name="name" placeholder="Full Name" required>
<input name="email" type="email" placeholder="Email Address" required>
<input type="password" name="password" placeholder="Password" required>
<input name="phone" placeholder="WhatsApp Number" required>

<select name="category" required>
<option value="">Select Category</option>
<option value="MR">Mr</option>
<option value="MRS">Mrs</option>
</select>

<textarea name="bio" placeholder="Tell about yourself..."></textarea>

<label class="file-box">
📸 Upload Your Photo
<input type="file" name="photo" required>
</label>

<button>🚀 Register Now</button>

</form>

</div>

</body>
</html>