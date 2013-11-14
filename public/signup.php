<?php
	require_once('../includes/initialize.php');

	$message = "";
	
	if($session->is_logged_in()){
		redirect_to("profile.php");
	}

	//Remember to give your form's submit tag a name="submit" attribute
	if(isset($_POST['submit'])){
		$user = new User();
		$user->user_name = trim($_POST['user_name']);
		$user->password = trim($_POST['password']);
		$user->first_name = trim($_POST['first_name']);
		$user->last_name = trim($_POST['last_name']);
		$user->email = trim($_POST['email']);
		$user->user_type = trim($_POST['user_type']);		
		
		if($user->save()){
			log_action("Sign up", "\"{$user->user_name}\" Signed up.");
			$session->login($user);
			// was originally just $user_name
			log_action("Login", "\"{$user->user_name}\" logged in.");
			redirect_to("photo_upload.php");
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
				<h2>Sign Up For Free!</h2>
				<?php echo output_message($message); ?>
			</div>
			<div id="login" class="grid_12">
				<div id="login_box" >
    			<form class="rounded" action="signup.php" method="post">
    				<h4>Create Your Account To Get Started</h4>
		  			<p>Username:<input type="text" name="user_name" maxlength="30" value="" /></p>
		  			<p>Your Email:<input type="text" name="email" maxlength="40" value="" /></p>		  			
		  			<p>First Name:<input type="text" name="first name" maxlength="30" value="" /></p>
		  			<p>Last Name:<input type="text" name="last_name" maxlength="30" value="" /></p>
				   	<p>Password:<input type="password" name="password" maxlength="30" value="<?php echo htmlentities($password); ?>" /></p>	
				   	<p>What kind of user are you: <select name="user_type">
				   		<option value="friend">Friend</option>
				   		<option value="family">Family</option>
				   		<option value="company">Company</option>
				   		<option value="other">Other</option>
				   	</select></p>			   	
			       	<p><input type="submit" name="submit" value="Sign Up" /></p>
		  		</form>
		  		</div>
		  </div>
    	</div>
    	<div id="footer" class="grid_12">Copyright <?php echo date("Y", time()); ?>, Alan DeLonga</div>
    </div>
  </body>
</html>
<?php if(isset($database)) { $database->close_connection(); } ?>
