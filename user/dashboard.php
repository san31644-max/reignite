<?php
require_once '../includes/auth.php';
require_once '../config/database.php';

$user_id = $_SESSION['user_id'];

// GET USER VOTES
$userVotes = [];
$voteQuery = $conn->prepare("SELECT category FROM votes WHERE user_id=?");
$voteQuery->bind_param("i", $user_id);
$voteQuery->execute();
$resultVotes = $voteQuery->get_result();

while ($row = $resultVotes->fetch_assoc()) {
    $userVotes[] = $row['category'];
}

// GET CONTESTANTS
$contestants = $conn->query("SELECT * FROM contestants WHERE approved=1 ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Vote - Reignite</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #020617;
    color: white;
    margin: 0;
}

/* HEADER */
header {
    display: flex;
    justify-content: space-between;
    padding: 20px 40px;
    background: #000;
}

header h2 {
    color: #facc15;
}

/* SECTION */
.section {
    padding: 40px;
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
    transition: 0.3s;
}

.card:hover {
    transform: scale(1.05);
    box-shadow: 0 0 20px #facc15;
}

.card img {
    width: 100%;
    height: 260px;
    object-fit: cover;
    border-radius: 10px;
}

/* BUTTON */
button {
    padding: 10px 20px;
    border: none;
    border-radius: 20px;
    background: linear-gradient(45deg, #facc15, #f97316);
    cursor: pointer;
    font-weight: bold;
    margin-top: 10px;
}

.disabled {
    background: gray;
    cursor: not-allowed;
}

/* CATEGORY TITLE */
.category-title {
    margin-top: 40px;
    font-size: 28px;
    color: #38bdf8;
}
</style>
</head>

<body>

<header>
    <h2>🗳️ Vote Now</h2>
    <a href="../auth/logout.php" style="color:white;">Logout</a>
</header>

<div class="section">

<?php
$categories = ['MR' => 'Mr', 'MRS' => 'Mrs'];

foreach ($categories as $key => $label):
?>

<h2 class="category-title"><?php echo $label; ?> Category</h2>

<div class="cards">

<?php
$result = $conn->query("SELECT * FROM contestants WHERE approved=1 AND category='$key'");

if ($result->num_rows > 0):
while ($row = $result->fetch_assoc()):
?>

<div class="card">
    <img src="../uploads/<?php echo $row['photo'] ?: 'default.jpg'; ?>">
    <h3><?php echo htmlspecialchars($row['name']); ?></h3>

    <?php if (in_array($key, $userVotes)): ?>
        <button class="disabled">Already Voted</button>
    <?php else: ?>
        <form method="POST" action="vote.php">
            <input type="hidden" name="contestant_id" value="<?php echo $row['id']; ?>">
            <input type="hidden" name="category" value="<?php echo $key; ?>">
            <button type="submit">Vote</button>
        </form>
    <?php endif; ?>

</div>

<?php endwhile; else: ?>
    <p>No contestants in this category</p>
<?php endif; ?>

</div>

<?php endforeach; ?>

</div>

</body>
</html>