<?php
	require_once('../../includes/initialize.php');

	$message = "";
	
	if($session->is_logged_in()){
		redirect_to("index.php");
	}

	//Remember to give your form's submit tag a name="submit" attribute
	if(isset($_POST['submit'])){
		$user_name = trim($_POST['user_name']);
		$password = trim($_POST['password']);
		
		// check database to seeif the username/password exist		
		$found_user = User::authenticate($user_name, $password);
		
		if($found_user){
			$session->login($found_user);
			// was originally just $user_name
			log_action("Login", "\"{$found_user->user_name}\" logged in.");

			redirect_to("index.php");
		} else {
			// username/password combo was not found in the database
			$message = "Username/password combination incorrect.";
		}
	} else {
		$user_name = "";
		$password = "";
	}

?>
<html>
  <head>
    <title>Photo Gallery</title>
    <link href="../stylesheets/main.css" media="all" rel="stylesheet" type="text/css" />
  </head>
  <body>
    <div id="header">
      <h1>Photo Gallery</h1>
    </div>
    <div id="main">
		<h2>Staff Login</h2>
		<?php echo output_message($message); ?>

		<form action="login.php" method="post">
		  <table>
		    <tr>
		      <td>Username:</td>
		      <td>
		        <input type="text" name="user_name" maxlength="30" value="<?php echo htmlentities($user_name); ?>" />
		      </td>
		    </tr>
		    <tr>
		      <td>Password:</td>
		      <td>
		        <input type="password" name="password" maxlength="30" value="<?php echo htmlentities($password); ?>" />
		      </td>
		    </tr>
		    <tr>
		      <td colspan="2">
		        <input type="submit" name="submit" value="Login" />
		      </td>
		    </tr>
		  </table>
		</form>
    </div>
    <div id="footer">Copyright <?php echo date("Y", time()); ?>, Alan DeLonga</div>
  </body>
</html>
<?php if(isset($database)) { $database->close_connection(); } ?>
