<?php
require_once "pdo.php";
session_start();

if ( ! isset($_SESSION['user_id']) )
    die("ACCESS DENIED");

// Guardian: Make sure that profile_id is present
if ( ! isset($_REQUEST['profile_id']) ) {
    $_SESSION['error'] = "Missing profile_id";
    header('Location: index.php');
    return;
}

// Does this profile_id exist and does user_id have access?
$stmt = $pdo->prepare('SELECT profile_id FROM Profile
                   WHERE profile_id = :pid AND user_id = :uid');
$stmt->execute(array(':pid' => $_REQUEST['profile_id'], ':uid' => $_SESSION['user_id']));
if (!$stmt->rowCount())
    die('ACCESS DENIED');

// Yes, so let user delete profile
if ( isset($_POST['delete']) && isset($_POST['profile_id']) ) {
    $sql = "DELETE FROM Profile WHERE profile_id = :pid LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':pid' => $_POST['profile_id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: index.php' ) ;
    return;
}

$stmt = $pdo->prepare("SELECT first_name, last_name FROM Profile where profile_id = :pid");
$stmt->execute(array(":pid" => $_REQUEST['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}

?>
<h1>Delete Profile</h1>
<p>Confirm: Deleting <?= htmlentities($row['first_name'] . ' ' . htmlentities($row['last_name'])); ?></p>

<form method="post">
<input type="hidden" name="profile_id" value="<?= $_REQUEST['profile_id'] ?>">
<input type="submit" value="Delete" name="delete">
<a href="index.php">Cancel</a>
</form>
