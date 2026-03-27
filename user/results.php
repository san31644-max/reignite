<?php
session_start();
require_once '../config/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>🏆 Results - Reignite</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    background: #020617;
    color: white;
    font-family: Arial, sans-serif;
}

h1 {
    text-align: center;
    color: gold;
}

/* SECTION */
.section {
    margin: 40px 20px;
}

/* TITLE */
.title {
    text-align: center;
    font-size: 28px;
    color: #38bdf8;
}

/* PODIUM */
.podium {
    display: flex;
    justify-content: center;
    align-items: flex-end;
    gap: 20px;
    margin: 30px 0;
}

.podium div {
    text-align: center;
}

.first { height: 200px; }
.second { height: 150px; }
.third { height: 120px; }

.podium img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid gold;
}

.box {
    background: gold;
    color: black;
    padding: 10px;
    border-radius: 10px;
    margin-top: 10px;
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
    background: #111;
    margin: 8px;
    padding: 12px;
    border-radius: 10px;
    display: flex;
    justify-content: space-between;
}
</style>
</head>

<body>

<h1>🏆 Live Results</h1>

<?php
function getResults($conn, $category) {
    $sql = "
    SELECT c.id, c.name, c.photo, COUNT(v.id) as votes
    FROM contestants c
    LEFT JOIN votes v ON c.id = v.contestant_id
    WHERE c.category='$category' AND c.approved=1
    GROUP BY c.id
    ORDER BY votes DESC
    ";
    return $conn->query($sql);
}

$categories = [
    'MR' => '👑 Mr Category',
    'MRS' => '👑 Mrs Category'
];

foreach ($categories as $key => $label):

$result = getResults($conn, $key);

$top = [];
$others = [];
$count = 0;

while ($row = $result->fetch_assoc()) {
    if ($count < 3) {
        $top[] = $row;
    } else {
        $others[] = $row;
    }
    $count++;
}
?>

<div class="section">

<div class="title"><?php echo $label; ?></div>

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
<div>#<?php echo $rank++; ?></div>
<div><?php echo $row['name']; ?></div>
<div><?php echo $row['votes']; ?> votes</div>
</div>

<?php endforeach; ?>

</div>

</div>

<?php endforeach; ?>

</body>
</html>