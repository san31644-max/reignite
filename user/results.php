<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>🏆 Results - Reignite</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">

<style>
body {
    background: radial-gradient(circle at top, #0f172a, #020617);
    color: white;
    font-family: 'Poppins', sans-serif;
    overflow-x: hidden;
}

/* TITLE */
h1 {
    text-align: center;
    color: #facc15;
    font-size: 40px;
    margin-top: 20px;
}

/* SECTION */
.section {
    margin: 60px 20px;
}

/* CATEGORY TITLE */
.title {
    text-align: center;
    color: #38bdf8;
    font-size: 30px;
    margin-bottom: 20px;
}

/* 🏆 PODIUM */
.podium {
    display: flex;
    justify-content: center;
    align-items: flex-end;
    gap: 30px;
    margin: 50px 0;
}

.podium div {
    text-align: center;
    transition: 0.4s;
}

.podium img {
    width: 130px;
    height: 130px;
    border-radius: 50%;
    object-fit: cover;
}

/* WINNER SPECIAL */
.first img {
    width: 150px;
    height: 150px;
    border: 4px solid gold;
    box-shadow: 0 0 30px gold;
}

.second img {
    border: 3px solid silver;
}

.third img {
    border: 3px solid #cd7f32;
}

.box {
    margin-top: 10px;
    padding: 10px;
    border-radius: 12px;
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(10px);
}

/* LIST */
.list {
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* CARD */
.card {
    width: 85%;
    background: rgba(255,255,255,0.05);
    margin: 10px;
    padding: 15px;
    border-radius: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: 0.3s;
}

.card:hover {
    transform: scale(1.03);
    box-shadow: 0 0 15px rgba(250,204,21,0.3);
}

.rank {
    color: #facc15;
    font-weight: bold;
}

/* MOBILE */
@media(max-width: 768px){
    .podium {
        flex-direction: column;
        align-items: center;
    }

    .card {
        width: 95%;
    }
}
</style>
</head>

<body>

<h1>🏆 Live Results</h1>

<?php
function getResults($conn, $category) {
    $query = "
    SELECT c.*, COUNT(v.id) AS votes
    FROM contestants c
    LEFT JOIN votes v ON c.id = v.contestant_id
    WHERE c.category='$category' AND c.approved=1
    GROUP BY c.id
    ORDER BY votes DESC
    ";
    return $conn->query($query);
}

$categories = ['MR' => '👑 Mr Category', 'MRS' => '👑 Mrs Category'];

foreach ($categories as $key => $title):
$result = getResults($conn, $key);

$top = [];
$others = [];
$count = 0;

while ($row = $result->fetch_assoc()) {
    if ($count < 3) $top[] = $row;
    else $others[] = $row;
    $count++;
}
?>

<div class="section">

<div class="title"><?php echo $title; ?></div>

<!-- 🏆 PODIUM -->
<div class="podium">

<?php if(isset($top[1])): ?>
<div class="second">
<img src="../uploads/<?php echo $top[1]['photo']; ?>">
<div class="box">
🥈 <?php echo $top[1]['name']; ?><br>
<?php echo $top[1]['votes']; ?> votes
</div>
</div>
<?php endif; ?>

<?php if(isset($top[0])): ?>
<div class="first">
<img src="../uploads/<?php echo $top[0]['photo']; ?>">
<div class="box">
🥇 <?php echo $top[0]['name']; ?><br>
<strong><?php echo $top[0]['votes']; ?> votes</strong>
</div>
</div>
<?php endif; ?>

<?php if(isset($top[2])): ?>
<div class="third">
<img src="../uploads/<?php echo $top[2]['photo']; ?>">
<div class="box">
🥉 <?php echo $top[2]['name']; ?><br>
<?php echo $top[2]['votes']; ?> votes
</div>
</div>
<?php endif; ?>

</div>

<!-- 📊 OTHER CONTESTANTS -->
<div class="list">

<?php
$rank = 4;
foreach ($others as $row):
?>

<div class="card">
<div class="rank">#<?php echo $rank++; ?></div>
<div><?php echo $row['name']; ?></div>
<div><?php echo $row['votes']; ?> votes</div>
</div>

<?php endforeach; ?>

</div>

</div>

<?php endforeach; ?>

</body>
</html>