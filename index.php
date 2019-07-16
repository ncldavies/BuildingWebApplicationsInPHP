<?php
session_start();

require_once 'pdo.php';

$stmt = $pdo->query("SELECT profile_id, user_id, first_name, last_name, headline FROM Profile");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
<title>Nicholas Christian Leigh Davies' - Profile Database - ff00c024</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Nicholas Davies' Resume Registry</h1>

<p><?php
if (!isset($_SESSION['name']))  // not logged in
    echo '<a href="login.php">Please log in</a>'; ?></p>

<?php
    if ( isset($_SESSION['success']) ) {
      echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
      unset($_SESSION['success']);
    } elseif ( isset($_SESSION['error']) ) {
        echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
        unset($_SESSION['error']);
    }
?>

<?php if (count($rows)): // display table ?>
    <table border="1">
    <tr style="font-weight: bold">
    	<td>Name</td>
    	<td>Headline</td>
    	<?php if (isset($_SESSION['name'])) echo '<td>Actions</td>'; ?>
    </tr>
    <?php
    foreach ( $rows as $row ) {
        echo '<tr><td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>';
        echo('<td><a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['headline']).'</a></td>');
        
        if (isset($_SESSION['name']) && $row['user_id'] == $_SESSION['user_id']) {
            echo '<td><a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ';
            echo '<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a></td>';
        }
            
        echo "</tr>\n";
    }
    ?>
    </table>
<?php endif; ?>
<?php if (isset($_SESSION['name'])):  // logged in ?>
<ul>
    <li><a href="add.php">Add New Entry</a></li>
	<li><a href="logout.php">Logout</a></li>
</ul>
<?php endif; ?>

</div>
</body>
</html>

