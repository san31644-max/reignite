<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['contestant_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_SESSION['contestant_id'];
$msg = "";

// ================= UPDATE PROFILE =================
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $bio = $_POST['bio'];

    if (!empty($_FILES['photo']['name'])) {
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $newName = time() . "." . $ext;
        move_uploaded_file($_FILES['photo']['tmp_name'], "../uploads/" . $newName);

        $conn->query("UPDATE contestants SET photo='$newName' WHERE id=$id");
    }

    $conn->query("UPDATE contestants SET name='$name', phone='$phone', bio='$bio' WHERE id=$id");
    $msg = "✅ Profile Updated!";
}

// ================= CHANGE PASSWORD =================
if (isset($_POST['pass'])) {
    $p = password_hash($_POST['newpass'], PASSWORD_DEFAULT);
    $conn->query("UPDATE contestants SET password='$p' WHERE id=$id");
    $msg = "🔐 Password Changed!";
}

// ================= DELETE ACCOUNT =================
if (isset($_POST['delete'])) {
    $conn->query("DELETE FROM contestants WHERE id=$id");
    session_destroy();
    header("Location: ../index.php");
    exit();
}

// ================= FETCH USER =================
$user = $conn->query("SELECT * FROM contestants WHERE id=$id")->fetch_assoc();

// ================= VOTES =================
$votes = $conn->query("SELECT COUNT(*) as c FROM votes WHERE contestant_id=$id")->fetch_assoc()['c'];

// ================= RANK (FIXED QUERY) =================
$q = $conn->query("
SELECT c.id, COUNT(v.id) as v
FROM contestants c
LEFT JOIN votes v ON c.id = v.contestant_id
WHERE c.category = '".$user['category']."'
GROUP BY c.id
ORDER BY v DESC
");

$rank = 1;
while ($row = $q->fetch_assoc()) {
    if ($row['id'] == $id) break;
    $rank++;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Contestant Dashboard</title>

<style>
body {
    background: linear-gradient(135deg,#020617,#0f172a);
    color: white;
    font-family: 'Poppins', sans-serif;
    text-align: center;
}

/* LAYOUT */
.container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

/* CARD */
.card {
    background: rgba(255,255,255,0.05);
    padding: 20px;
    border-radius: 15px;
    width: 280px;
    backdrop-filter: blur(10px);
    box-shadow: 0 0 20px rgba(250,204,21,0.2);
}

/* IMAGE */
img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid gold;
}

/* INPUTS */
input, textarea {
    width: 90%;
    padding: 10px;
    margin: 5px;
    border-radius: 8px;
    border: none;
}

/* BUTTONS */
button {
    padding: 10px 15px;
    margin: 5px;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    font-weight: bold;
}

.gold { background: gold; }
.blue { background: #38bdf8; }
.red { background: red; color: white; }

/* MESSAGE */
.msg {
    color: lightgreen;
    margin-bottom: 10px;
}
</style>

</head>
<body>

<h1>🎓 Contestant Dashboard</h1>

<p class="msg"><?php echo $msg; ?></p>

<div class="container">

<!-- PROFILE -->
<div class="card">
<img src="../uploads/<?php echo $user['photo']; ?>">

<h3><?php echo $user['name']; ?></h3>

<p>🆔 ID: <b><?php echo $user['contestant_code']; ?></b></p>
<p>📊 Votes: <b><?php echo $votes; ?></b></p>
<p>🏆 Rank: <b>#<?php echo $rank; ?></b></p>
<p>Category: <?php echo $user['category']; ?></p>
</div>

<!-- EDIT -->
<div class="card">
<h3>✏️ Edit Profile</h3>

<form method="POST" enctype="multipart/form-data">
<input name="name" value="<?php echo $user['name']; ?>" required>
<input name="phone" value="<?php echo $user['phone']; ?>">
<textarea name="bio"><?php echo $user['bio']; ?></textarea>
<input type="file" name="photo">
<button class="gold" name="update">Update</button>
</form>
</div>

<!-- PASSWORD -->
<div class="card">
<h3>🔐 Change Password</h3>

<form method="POST">
<input type="password" name="newpass" placeholder="New Password" required>
<button class="blue" name="pass">Change</button>
</form>
</div>

<!-- ACTIONS -->
<div class="card">
<h3>⚡ Actions</h3>

<a href="../user/results.php">
<button class="blue">🏆 View Results</button>
</a>

<a href="logout.php">
<button class="red">🚪 Logout</button>
</a>

<form method="POST" onsubmit="return confirm('Delete account?');">
<button class="red" name="delete">🗑️ Delete</button>
</form>

</div>

</div>

</body>
</html>