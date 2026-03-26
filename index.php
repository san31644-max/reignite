<?php
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reignite - Rivisanda Central College</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: #020617;
    color: white;
    overflow-x: hidden;
}

/* NAVBAR */
header {
    display: flex;
    justify-content: space-between;
    padding: 20px 50px;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(10px);
    position: fixed;
    width: 100%;
    z-index: 100;
}

header h1 {
    color: #facc15;
    letter-spacing: 2px;
}

header a {
    color: white;
    margin-left: 20px;
    text-decoration: none;
    transition: 0.3s;
}

header a:hover {
    color: #facc15;
}

/* HERO */
.hero {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.9)),
                url('assets/images/bg.jpg') center/cover;
    animation: fadeIn 2s ease-in;
}

.hero h2 {
    font-size: 55px;
    text-shadow: 0 0 20px #facc15;
}

.hero span {
    color: #38bdf8;
}

.btn {
    padding: 12px 28px;
    background: linear-gradient(45deg, #facc15, #f97316);
    border: none;
    border-radius: 30px;
    margin: 10px;
    cursor: pointer;
    font-weight: bold;
    transition: 0.3s;
}

.btn:hover {
    transform: scale(1.1);
}

/* COUNTDOWN */
.countdown {
    margin-top: 20px;
    font-size: 22px;
}

/* SECTION */
.section {
    padding: 80px 50px;
    text-align: center;
}

.cards {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 25px;
}

.card {
    background: rgba(255,255,255,0.05);
    border-radius: 15px;
    width: 260px;
    padding: 15px;
    backdrop-filter: blur(10px);
    transition: 0.4s;
}

.card:hover {
    transform: translateY(-10px) scale(1.05);
    box-shadow: 0 0 25px #facc15;
}

.card img {
    width: 100%;
    height: 260px;
    object-fit: cover;
    border-radius: 12px;
}

/* FOOTER */
footer {
    text-align: center;
    padding: 20px;
    background: #000;
}

/* ANIMATION */
@keyframes fadeIn {
    from {opacity: 0; transform: translateY(20px);}
    to {opacity: 1; transform: translateY(0);}
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

<!-- HERO -->
<section class="hero">
    <div>
        <h2>Mr & Mrs <span>Reignite</span></h2>
        <p>Rivisanda Central College<br>2022 O/L & 2025 A/L Batches</p>

        <div class="countdown" id="countdown"></div>

        <a href="user/dashboard.php"><button class="btn">Vote Now</button></a>
        <a href="contestant/register.php"><button class="btn">Join Contest</button></a>
    </div>
</section>

<!-- CONTESTANTS -->
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
    <p>© <?php echo date("Y"); ?> Reignite | Rivisanda Central College</p>
</footer>

<!-- COUNTDOWN SCRIPT -->
<script>
const countdown = document.getElementById("countdown");

// SET YOUR EVENT DATE HERE
const eventDate = new Date("April 30, 2026 23:59:59").getTime();

setInterval(() => {
    const now = new Date().getTime();
    const gap = eventDate - now;

    const d = Math.floor(gap / (1000 * 60 * 60 * 24));
    const h = Math.floor((gap / (1000 * 60 * 60)) % 24);
    const m = Math.floor((gap / (1000 * 60)) % 60);
    const s = Math.floor((gap / 1000) % 60);

    countdown.innerHTML = `Voting ends in: ${d}d ${h}h ${m}m ${s}s`;
}, 1000);
</script>

</body>
</html>