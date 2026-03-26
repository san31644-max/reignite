<?php
require_once '../config/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Live Leaderboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">

<style>
body {
    background: #020617;
    color: white;
    font-family: 'Poppins', sans-serif;
}

h1 {
    text-align: center;
    color: gold;
}

.section {
    margin: 40px;
}

.title {
    text-align: center;
    font-size: 28px;
    color: #38bdf8;
}

.container {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.card {
    width: 80%;
    background: rgba(255,255,255,0.05);
    margin: 10px;
    padding: 15px;
    border-radius: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    backdrop-filter: blur(10px);
}

.rank {
    font-size: 22px;
    color: gold;
    width: 50px;
}

img {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    object-fit: cover;
}

.name {
    flex: 1;
    margin-left: 15px;
}

.votes {
    font-size: 18px;
    color: #22c55e;
}
</style>
</head>

<body>

<h1>🏆 Live Leaderboard</h1>

<!-- MR SECTION -->
<div class="section">
    <div class="title">👑 Mr Category</div>
    <div class="container">

    <?php
    $query = "
    SELECT c.*, COUNT(v.id) AS votes
    FROM contestants c
    LEFT JOIN votes v ON c.id = v.contestant_id
    WHERE c.category='MR' AND c.approved=1
    GROUP BY c.id
    ORDER BY votes DESC
    ";

    $result = $conn->query($query);
    $rank = 1;

    while($row = $result->fetch_assoc()):
    ?>

    <div class="card">
        <div class="rank">#<?php echo $rank++; ?></div>
        <img src="../uploads/<?php echo $row['photo']; ?>">
        <div class="name"><?php echo htmlspecialchars($row['name']); ?></div>
        <div class="votes"><?php echo $row['votes']; ?> votes</div>
    </div>

    <?php endwhile; ?>

    </div>
</div>

<!-- MRS SECTION -->
<div class="section">
    <div class="title">👑 Mrs Category</div>
    <div class="container">

    <?php
    $query = "
    SELECT c.*, COUNT(v.id) AS votes
    FROM contestants c
    LEFT JOIN votes v ON c.id = v.contestant_id
    WHERE c.category='MRS' AND c.approved=1
    GROUP BY c.id
    ORDER BY votes DESC
    ";

    $result = $conn->query($query);
    $rank = 1;

    while($row = $result->fetch_assoc()):
    ?>

    <div class="card">
        <div class="rank">#<?php echo $rank++; ?></div>
        <img src="../uploads/<?php echo $row['photo']; ?>">
        <div class="name"><?php echo htmlspecialchars($row['name']); ?></div>
        <div class="votes"><?php echo $row['votes']; ?> votes</div>
    </div>

    <?php endwhile; ?>

    </div>
</div>

<!-- AUTO REFRESH -->
<script>
setTimeout(() => {
    location.reload();
}, 5000); // refresh every 5 seconds
</script>

</body>
</html>