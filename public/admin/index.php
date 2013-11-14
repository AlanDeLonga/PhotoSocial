<?php
require_once('../../includes/functions.php');
require_once('../../includes/session.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
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
		<h2>Menu</h2>
		<?php echo output_message($message); ?>
		<div class="nav_links">
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="show_photos.php">Show Photos</a></li>
				<li><a href="photo_upload.php">Upload Photos</a></li>
				<li><a href="logfile.php">Logs</a></li>
			</ul>
			<br><br>
			<form action="logout.php" method="post">
				<input type="submit" name="log_out" value="logout" />
			</form>
		</div>	
			
			
	</div>		
    <div id="footer">Copyright <?php echo date("Y", time()); ?>, Alan DeLonga</div>
  </body>
</html>
