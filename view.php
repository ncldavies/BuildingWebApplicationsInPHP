<?php

require_once 'pdo.php';
session_start();

$profile = null;
if (isset($_GET['profile_id']) && is_numeric($_GET['profile_id'])) {
    $stmt = $pdo->prepare("
        SELECT first_name, last_name, email, headline, summary
        FROM Profile
        WHERE profile_id = :id
        LIMIT 1
    ");
    $stmt->execute(array(':id' => $_GET['profile_id']));
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$profile) {
    $_SESSION['error'] = 'Invalid profile_id';
    header('Location: index.php');
    return;
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Nicholas Christian Leigh Davies' Profile View</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
    <h1>Profile information</h1>
    
    <p>First Name: <?= htmlentities($profile['first_name']); ?></p>
    
    <p>Last Name: <?= htmlentities($profile['last_name']); ?></p>
    
    <p>Email: <?= htmlentities($profile['email']); ?></p>
    
    <p>Headline:<br/>
    <?= htmlentities($profile['headline']); ?></p>
    
    <p>Summary:<br/>
    <?= htmlentities($profile['summary']); ?></p>
    
    <p><a href="index.php">Done</a></p>
</div>
</body>
</html>