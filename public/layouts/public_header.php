<html>
  <head>
    <title>Photo Social</title>
    <link href="stylesheets/main.css" media="all" rel="stylesheet" type="text/css" />
   	<link href="stylesheets/960/960.css" media="all" rel="stylesheet" type="text/css" />
	<link href="stylesheets/960/reset.css" media="all" rel="stylesheet" type="text/css" />
	<link href="stylesheets/960/text.css" media="all" rel="stylesheet" type="text/css" />
  </head>
  <body>
  	<div id="container" class="container_12">
  		<div id="header" class="grid_12">
			<h1><a id="main_link" href="index.php">PhotoSocial </a>
		 	 <span class="top_links">
		 	 	<a href="index.php">Home</a>
		 	 	<!--<a href="index.php">About</a> -->
		 	 	<a href="contact.php">Contact</a>
		 	 	<a href="browse.php">Browse</a>
		 	 	<?php
		 	 		if($session->is_logged_in()){ ?>
		 	 			<a href="profile.php">Profile</a>
		 	 			<a href="photo_upload.php">Upload</a>		  		
		  				<a href="logout.php">Logout</a>
		 	 	<?php } else {	?>
		  			<a href="signup.php">Sign Up</a>
		  			<a href="login.php">Login</a>
		  		<?php } ?>
		  	</span>	
		  </h1>
		</div>