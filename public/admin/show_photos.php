<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php

	// the current page number
	$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
	
	// records per page
	$per_page = 4;
	
	// total record count
	$total_count = Photograph::count_all();
	
	// Find subset of photos
	$pagination = new Pagination($page, $per_page, $total_count);
	
	// Instead of finding all records , just find the records for this page
	$sql = "SELECT * FROM photographs ";
	$sql .= "LIMIT {$per_page} ";
	$sql .= "OFFSET {$pagination->offset()}";
	$photos = Photograph::find_by_sql($sql);	

	// keep track of current page by adding to links ?page=$page
	// or storing $page in the $session for when you navigate from this page
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
		<h2>Uploaded Photos</h2>
			<?php echo output_message($message); ?>
		<div>
			<?php
				foreach ($photos as $photo){ ?>
				<div class="photo_container">
					<center><?php echo $photo->caption;?></center>
					<img class="photo_display" src="../<?php echo $photo->image_path();?>"/>
					<p>&nbsp;&nbsp;&nbsp;
					<a href="delete_photo.php?id=<?php echo $photo->id;?>">Delete</a>&nbsp;&nbsp;&nbsp;
					<a href="edit_photo.php?id=<?php echo $photo->id;?>">Edit <?php echo count($photo->comments());?> Comments</a>
					</p>
				</div>
					<?php
					// "<div class='photo_display'> </div>"
				}
				
			?>
		</div>
		<div id="pagination" style="clear: both;">
			<?php				
			
				if($pagination->total_pages() > 1){				
					if($pagination->has_previous_page()){
						echo " <a href=\"show_photos.php?page=";
						echo $pagination->previous_page();
						echo "\">&laquo; Prev </a>&nbsp;&nbsp;";
					}
					
					for($i=1; $i <= $pagination->total_pages(); $i++){
						if($i == $page){
							echo "<span class=\"selected\">{$i}</span> ";
						}else{
							echo "<a href=\"show_photos.php?page={$i}\">{$i}</a>&nbsp;&nbsp;";
						}
					}
					
					if($pagination->has_next_page()){
						echo " <a href=\"show_photos.php?page=";
						echo $pagination->next_page();
						echo "\">Next &raquo;</a>";
					}				
				}
			?>
		</div>
		<br><br>
		<div class="nav_links">
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="photo_upload.php">Upload Photos</a></li>
				<li><a href="logfile.php">Logs</a></li>
			</ul>
			<form action="logout.php" method="post">
				<input type="submit" name="log_out" value="logout" />
			</form>
		</div>
		
		
	</div>
		
    <div id="footer">Copyright <?php echo date("Y", time()); ?>, Alan DeLonga</div>
  </body>
</html>
