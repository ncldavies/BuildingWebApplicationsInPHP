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

    
    
// yes, so let user edit...
if (isset($_POST['edit'])) {
    
    if (strlen(trim($_POST['first_name'])) < 1
        || strlen(trim($_POST['last_name'])) < 1
        || strlen(trim($_POST['email'])) < 1
        || strlen(trim($_POST['headline'])) < 1
        || strlen(trim($_POST['summary'])) < 1) {
            $_SESSION['error'] = 'All fields are required';
    } elseif (strpos($_POST['email'], '@') === false) {
        $_SESSION['error'] = 'Email address must contain @';
    } else {
        
        // For your first implementation of handling the POST data in edit.php just delete all the old Postion entries and re-insert them:
        
        $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
        $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));
        
        $insert_position = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description)
                               VALUES ( :pid, :rank, :year, :desc)');
        
        for ($i = 0; $i < count($_POST['positions']['year']); $i++) {
            $insert_position->execute(array(
                ':pid' => $profile_id,
                ':rank' => $i,
                ':year' => $_POST['positions']['year'][$i],
                ':desc' => $_POST['positions']['desc'][$i])
                );
        }
        
        $stmt = $pdo->prepare('UPDATE Profile SET
                                  first_name = :fn,
                                  last_name = :ln,
                                  email = :em,
                                  headline = :he,
                                  summary = :su
                               WHERE profile_id = :pid');
        if ($stmt->execute(array(
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST['last_name'],
            ':em' => $_POST['email'],
            ':he' => $_POST['headline'],
            ':su' => $_POST['summary'],
            ':pid' => $_REQUEST['profile_id']
        ))) {
            $_SESSION['success'] = "Record updated";
            header("Location: index.php");
            return;
        }
    }
    // validation failed
    header("Location: edit.php?profile_id=" . $_REQUEST['profile_id']);
    return;
}

$stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :id");
$stmt->execute(array(":id" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Nicholas Davies's Login Page</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">

<h1>Editing Profile for <?= htmlentities($_SESSION['name']); ?></h1>

<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
?>

<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60" value="<?= htmlentities($row['first_name']) ?>"/></p>
<p>Last Name:
<input type="text" name="last_name" size="60" value="<?= htmlentities($row['last_name']) ?>"/></p>
<p>Email:
<input type="text" name="email" size="30" value="<?= htmlentities($row['email']) ?>"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80" value="<?= htmlentities($row['headline']) ?>"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80"><?= htmlentities($row['summary']) ?></textarea></p>
<input type="hidden" name="profile_id" value="<?= $_REQUEST['profile_id'] ?>">
<p>
	<input type="submit" name="edit" value="Save"/>
	<input type="button" onclick="location.href = 'index.php';" value="Cancel" />
</p>


<div id="position_fields">
<?php

$stmt = $pdo->prepare("SELECT * FROM Position where profile_id = :id");
$stmt->execute(array(":id" => $_GET['profile_id']));

foreach ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

?>
<div><p>Year: <input type="text" name="positions[year][]" value="" /> 
            <input type="button" value="-" \
                onclick="$(this).parent().parent().remove(); return false;"></p> 
            <textarea name="positions[desc][]" rows="8" cols="80"></textarea><p> 
            </div>

</div>

<?php } //end foreach $row ?>

</form>

</div>
</body>
</html>


