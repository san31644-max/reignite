<?php
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reignite - Rivisanda Central College</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">

<style>
* {
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body {
    font-family:'Poppins',sans-serif;
    background:#020617;
    color:white;
}

/* NAVBAR */
header {
    display:flex;
    justify-content:space-between;
    padding:20px 50px;
    background:rgba(0,0,0,0.6);
    backdrop-filter:blur(10px);
    position:fixed;
    width:100%;
    z-index:100;
}

header h1 {color:#facc15;}

header a {
    color:white;
    margin-left:20px;
    text-decoration:none;
}

/* 🎬 HERO WITH VIDEO */
.hero {
    position:relative;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    overflow:hidden;
}

/* VIDEO */
.bg-video {
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    object-fit:cover;
    z-index:-2;
}

/* DARK OVERLAY */
.video-overlay {
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.7);
    z-index:-1;
}

/* BOXES */
.dual-box {
    display:flex;
    gap:30px;
    flex-wrap:wrap;
    justify-content:center;
}

.box-card {
    position:relative;
    width:320px;
    height:420px;
    border-radius:20px;
    overflow:hidden;
    transition:0.4s;
}

.box-card img {
    width:100%;
    height:100%;
    object-fit:cover;
}

.overlay {
    position:absolute;
    inset:0;
    background:linear-gradient(to top, rgba(0,0,0,0.9), rgba(0,0,0,0.3));
    display:flex;
    flex-direction:column;
    justify-content:flex-end;
    padding:20px;
    text-align:center;
}

.overlay h3 {color:#facc15;}
.overlay p {font-size:14px;}

/* BUTTON */
.btn {
    padding:10px 20px;
    background:linear-gradient(45deg,#facc15,#f97316);
    border:none;
    border-radius:25px;
    cursor:pointer;
    margin-top:10px;
}

/* HOVER */
.box-card:hover {
    transform:scale(1.05);
    box-shadow:0 0 25px #facc15;
}

/* SECTION */
.section {
    padding:80px 20px;
    text-align:center;
}

/* CARDS */
.cards {
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
    gap:25px;
}

.card {
    background:rgba(255,255,255,0.05);
    border-radius:15px;
    width:260px;
    padding:15px;
}

.card img {
    width:100%;
    height:260px;
    object-fit:cover;
    border-radius:12px;
}

/* FOOTER */
footer {
    text-align:center;
    padding:20px;
    background:black;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<header>
    <h1>Reignite 2026</h1>
    <div>
        <a href="auth/login.php">Login</a>
        <a href="auth/register.php">Register</a>
    </div>
</header>

<!-- HERO WITH VIDEO -->
<section class="hero">

    <!-- 🎬 BACKGROUND VIDEO -->
    <video autoplay muted loop playsinline class="bg-video">
        <source src="assets/videos/bg.mp4" type="video/mp4">
    </video>

    <!-- DARK OVERLAY -->
    <div class="video-overlay"></div>

    <!-- BOXES -->
    <div class="dual-box">

        <!-- VOTERS -->
        <div class="box-card">
            <img src="assets/images/vote.jpg">
            <div class="overlay">
                <h3>🗳️ Vote Your Favorite</h3>
                <p>Support your friends and choose your winner</p>
                <a href="auth/login.php"><button class="btn">Start Voting</button></a>
            </div>
        </div>

        <!-- CONTESTANTS -->
        <div class="box-card">
            <img src="assets/images/contestant.jpg">
            <div class="overlay">
                <h3>🔥 Join the Contest</h3>
                <p>Register and compete</p>
                <a href="contestant/register.php"><button class="btn">Join Now</button></a>
            </div>
        </div>

    </div>

</section>

<!-- TOP CONTESTANTS -->
<section class="section">
<h2>🔥 Top Contestants</h2>

<div class="cards">
<?php
$result = $conn->query("SELECT * FROM contestants WHERE approved=1 LIMIT 6");

if ($result && $result->num_rows > 0):
while($row = $result->fetch_assoc()):
?>
<div class="card">
<img src="uploads/<?php echo $row['photo'] ?: 'default.jpg'; ?>">
<h3><?php echo htmlspecialchars($row['name']); ?></h3>
<p><?php echo $row['category']; ?></p>
</div>
<?php endwhile; else: ?>
<p>No contestants yet</p>
<?php endif; ?>
</div>
</section>

<!-- FOOTER -->
<footer>
<p>© <?php echo date("Y"); ?> Reignite</p>
</footer>

</body>
</html>