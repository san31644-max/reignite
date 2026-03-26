<?php
require_once '../config/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>🏆 Results - Reignite</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">

<style>
body {
    background: #020617;
    color: white;
    font-family: 'Poppins', sans-serif;
}

/* TITLE */
h1 {
    text-align: center;
    color: gold;
}

/* SECTION */
.section {
    margin: 50px 20px;
}

/* PODIUM */
.podium {
    display: flex;
    justify-content: center;
    align-items: flex-end;
    gap: 20px;
    margin: 40px 0;
}

.podium div {
    text-align: center;
}

.first {
    height: 200px;
}
.second {
    height: 150px;
}
.third {
    height: 120px;
}

.podium img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid gold;
}

.box {
    background: linear-gradient(45deg, #facc15, #f97316);
    border-radius: 10px;
    padding: 10px;
    margin-top: 10px;
    color: black;
    font-weight: bold;
}

/* LIST */
.list {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.card {
    width: 80%;
    background: rgba(255,255,255,0.05);
    margin: 8px;
    padding: 12px;
    border-radius: 12px;
    display: flex;
    justify-content: space-between;
}

.rank {
    color: gold;
}

/* CATEGORY TITLE */
.title {
    text-align: center;
    color: #38bdf8;
    font-size: 28px;
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

<!-- PODIUM -->
<div class="podium">

<?php if(isset($top[1])): ?>
<div class="second">
<img src="../uploads/<?php echo $top[1]['photo']; ?>">
<div class="box">🥈 <?php echo $top[1]['name']; ?><br><?php echo $top[1]['votes']; ?> votes</div>
</div>
<?php endif; ?>

<?php if(isset($top[0])): ?>
<div class="first">
<img src="../uploads/<?php echo $top[0]['photo']; ?>">
<div class="box">🥇 <?php echo $top[0]['name']; ?><br><?php echo $top[0]['votes']; ?> votes</div>
</div>
<?php endif; ?>

<?php if(isset($top[2])): ?>
<div class="third">
<img src="../uploads/<?php echo $top[2]['photo']; ?>">
<div class="box">🥉 <?php echo $top[2]['name']; ?><br><?php echo $top[2]['votes']; ?> votes</div>
</div>
<?php endif; ?>

</div>

<!-- OTHER CONTESTANTS -->
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