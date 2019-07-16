<?php // Do not put any HTML above this line

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}

session_start();

require_once 'pdo.php';

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $_SESSION['error'] = "Email and password are required";
    } elseif (strpos($_POST['email'], '@') === false) {
        $_SESSION['error'] = 'Email must have an at-sign (@)';
    } else {
        $salt = 'XyZzy12*_';
        $check = hash('md5', $salt . $_POST['pass']);
        $stmt = $pdo->prepare("SELECT user_id, name FROM users WHERE email = :email AND password = :pass LIMIT 1");
        $stmt->execute(array(
            ':email' => $_POST['email'],
            ':pass' => $check
        ));
        
        if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            error_log("Login success ".$_POST['email']);            
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            header("Location: index.php");
            return;
        } else {
            $_SESSION['error'] = "Incorrect password";
            error_log("Login fail ".$_POST['email']." $check");
        }
    }
    // a validation error occurred
    header('Location: login.php');
    return;
}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
<title>Nicholas Davies's Login Page</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>

<?php
if ( isset($_SESSION['error']) ) {
  echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
  unset($_SESSION['error']);
}
?>

<script type="text/javascript">
function doValidate() {
	console.log('Validating...');
	try {
		email = document.getElementById('email').value;
    	if (!email.length
    	    || !document.getElementById('pass').value.length) {
    		alert("Both fields must be filled out");
    		return false;
    	} else if (email.indexOf('@') == -1) {
        	alert('Invalid email Address');
        	return false;
    	}
    	return true;
	} catch(e) {
		return false;
	}
	return false;
}
</script>
<form method="POST">
<label for="email">Email</label>
<input type="text" name="email" id="email"><br/>
<label for="pass">Password</label>
<input type="text" name="pass" id="pass"><br/>
<input type="submit" value="Log In" onclick="return doValidate();">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>

</p>
</div>
</body>
