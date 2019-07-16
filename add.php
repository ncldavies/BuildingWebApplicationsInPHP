<?php
require_once "pdo.php";
session_start();

if ( ! isset($_SESSION['user_id']) )
    die("ACCESS DENIED");

if (isset($_POST['add'])) {
    if (strlen(trim($_POST['first_name'])) < 1
        || strlen(trim($_POST['last_name'])) < 1
        || strlen(trim($_POST['email'])) < 1
        || strlen(trim($_POST['headline'])) < 1
        || strlen(trim($_POST['summary'])) < 1) {
        $_SESSION['error'] = 'All fields are required';
    } elseif (strpos($_POST['email'], '@') === false) {
        $_SESSION['error'] = 'Email address must contain @';
    } else { // validate positions
        if (count($_POST['positions']['year']) < 9) {
            for ($i = 0; $i < count($_POST['positions']['year']); $i++) {
                if ( strlen($_POST['positions']['year'][$i]) == 0
                    || strlen($_POST['positions']['desc'][$i]) == 0 ) {
                        $_SESSION['error'] = "All fields are required";
                        break;
                } elseif ( ! is_numeric($_POST['positions']['year'][$i]) ) {
                    $_SESSION['error'] = "Position year must be numeric";
                    break;
                }
            }
        } else {
            $_SESSION['error'] = "Maximum of nine position entries exceeded";
        }
    }

    if (isset($_SESSION['error'])) {
        // validation failed
        header("Location: add.php");
        return;
    }
    
    $stmt = $pdo->prepare('
        INSERT INTO Profile
            (user_id, first_name, last_name, email, headline, summary)
        VALUES ( :uid, :fn, :ln, :em, :he, :su)');
    if ($stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary']
        ))) {

        $profile_id = $pdo->lastInsertId();

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

        $_SESSION['success'] = "Record added";
        header("Location: index.php");
        return;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Nicholas Christian Leigh Davies' Profile Registry</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">

<h1>Adding Profile for <?= htmlentities($_SESSION['name']); ?></h1>

<?php
if ( isset($_SESSION['error']) ) {
  echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
  unset($_SESSION['error']);
}
?>

<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60"/></p>
<p>Last Name:
<input type="text" name="last_name" size="60"/></p>
<p>Email:
<input type="text" name="email" size="30"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80"></textarea></p>

<p>
Position: <input type="submit" id="addPos" value="+">
<div id="position_fields">
</div>
</p>

<p>
	<input type="submit" name="add" value="Add New"/>
	<input type="button" onclick="location.href = 'index.php';" value="Cancel" />
</p>
</form>

<script>
$(document).ready(function(){
    $('#addPos').click(function(event){
        event.preventDefault();
        if ( $('#position_fields > div').length >= 9 ) {
            alert("Maximum of nine position entries exceeded");
            return;
        }
        $('#position_fields').append(
            '<div><p>Year: <input type="text" name="positions[year][]" value="" /> \
            <input type="button" value="-" \
                onclick="$(this).parent().parent().remove(); return false;"></p> \
            <textarea name="positions[desc][]" rows="8" cols="80"></textarea><p> \
            </div>');
    });
});
</script>

</div>
</body>
</html>
