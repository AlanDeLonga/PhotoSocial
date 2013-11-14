<?php
	require_once('../includes/initialize.php');

	$message = "";
	
	if($session->is_logged_in()){
		redirect_to("profile.php");
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
			redirect_to("profile.php");
		} else {
			// username/password combo was not found in the database
			$message = "Username/password combination incorrect.";
		}
	} else {
		$user_name = "";
		$password = "";
	}

?>
<?php include("layouts/public_header.php"); ?>
	<div id="main" class="grid_12">
    		<div id="title" class="grid_12" >
				<h2>Login</h2>
				<?php echo output_message($message); ?>
			</div>
			<div id="login" class="grid_12">
				<div id="login_box" >
    			<form class="rounded" action="login.php" method="post">
    				<h3>Sign in to upload photos and see the <br>new activity on your old photos</h3>
		  			<p>Username:<input type="text" name="user_name" maxlength="30" value="<?php echo htmlentities($user_name); ?>" /></p>
				   	<p>Password:<input type="password" name="password" maxlength="30" value="<?php echo htmlentities($password); ?>" /></p>
			       	<p><input type="submit" name="submit" value="Login" /></p>
		  		</form>
		  		</div>
		  </div>
    	</div>
    	<div id="footer" class="grid_12">Copyright <?php echo date("Y", time()); ?>, Alan DeLonga</div>
    </div>
  </body>
</html>
<?php if(isset($database)) { $database->close_connection(); } ?>
