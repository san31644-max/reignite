<?php
require_once '../config/database.php';

$message = "";

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
                $message = "❌ Error!";
            }

        } else {
            $message = "❌ Only images allowed!";
        }

    } else {
        $message = "❌ Upload image!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register Contestant</title>
<style>
body {background:#020617;color:white;font-family:sans-serif;text-align:center;}
form {background:#111;padding:20px;border-radius:10px;display:inline-block;}
input,select,textarea {display:block;margin:10px;padding:10px;width:250px;}
button {padding:10px;background:gold;border:none;}
</style>
</head>

<body>

<h2>Contestant Registration</h2>
<p><?php echo $message; ?></p>

<form method="POST" enctype="multipart/form-data">
<input name="name" placeholder="Name" required>
<input name="email" placeholder="Email" required>
<input name="phone" placeholder="WhatsApp" required>

<select name="category" required>
<option value="">Select</option>
<option value="MR">Mr</option>
<option value="MRS">Mrs</option>
</select>

<textarea name="bio" placeholder="Bio"></textarea>

<input type="file" name="photo" required>

<button>Register</button>
</form>

</body>
</html>