<?php
require_once('../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php

	// the current page number
	$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
	
	// records per page
	$per_page = 6;
	
	// total record count
	$total_count = Photograph::count_all_for_user($session->user_id);

	
	// Find subset of photos
	$pagination = new Pagination($page, $per_page, $total_count);
	
	// Instead of finding all records , just find the records for this page
	$sql = "SELECT * FROM photographs ";
	$sql .= "WHERE uid=".$session->user_id;
	$sql .= " LIMIT {$per_page} ";
	$sql .= "OFFSET {$pagination->offset()}";
	$photos = Photograph::find_by_sql($sql);	

	// keep track of current page by adding to links ?page=$page
	// or storing $page in the $session for when you navigate from this page
?>

<?php include("layouts/public_header.php"); ?>

		<div id="title" class="grid_12">
			<h2>Manage Photos</h2>
			<div id="flash_message" class="grid_12 rounded1 mng_msg" <?php
		    	if(isset($message) && $message != ''){
		    		echo "style='display: block'";
		    	} else {
		    		echo "style='display: none'";
		    	} ?>">
		    	<?php echo $message; ?>
		    	<p id="close">
		    		<a href="#" onmousedown="document.getElementById('flash_message').style.display = 'none'; return false;">close</a>
		    	</p>
	    	</div>
		</div>
		<div class="pagination grid_12" >
			<?php				
			
				if($pagination->total_pages() > 1){				
					if($pagination->has_previous_page()){
						echo " <a href=\"manage_photo.php?page=";
						echo $pagination->previous_page();
						echo "\">&laquo; Prev </a>&nbsp;&nbsp;";
					}
					
					for($i=1; $i <= $pagination->total_pages(); $i++){
						if($i == $page){
							echo "<span class=\"selected\">{$i}</span> ";
						}else{
							echo "<a href=\"manage_photo.php?page={$i}";
							echo "\">{$i}</a>&nbsp;&nbsp;";
						}
					}
					
					if($pagination->has_next_page()){
						echo " <a href=\"manage_photo.php?page=";
						echo $pagination->next_page();
						echo "\">Next &raquo;</a>";
					}				
				}
			?>
		</div>	
			
		<div id="photo_container" class="grid_9 rounded">
			<?php
				$count = 0;
				foreach ($photos as $photo){ ?>
				<div class="photos grid_3">				
					<p class="caption"><?php echo $photo->caption;?></p>		
					<?php 						
						if($photo->rate == 1){
							for($i=1+($count*10); $i<(11*($count+1)-$count); $i++){
						?>						
						
						<img id="star<?php echo $i ?>" class="browse_rating_star" src=
						<?php 
							// keeps stars always set at rating for photo when not being rated
							// mod 11 so rating is always 1-10
							if($photo->rating+($count*10) >= ($i)){ 
								echo "images/assets/rating_star.png";
							} else {
								echo "images/assets/rating_star_off.png";
							}
						?>
						/>
						<?php } 
						} else { echo "<br>";}?>
						
						
						<a href="edit_photo.php?id=<?php echo $photo->id; ?>">
						<img class="photo_display" src="<?php echo $photo->image_path();?>"/>
						</a>					
						<p>&nbsp;&nbsp;&nbsp;
						<a href="delete_photo.php?id=<?php echo $photo->id;?>">Delete</a>&nbsp;&nbsp;&nbsp;
						<a href="edit_comment.php?id=<?php echo $photo->id;?>">Edit <?php echo count($photo->comments());?> Comments</a>
						</p>
				</div>
			<?php } ?>
		</div>
		<div id="category_nav" class="grid_3 rounded">
			<h2>Account Options</h2>
			<ul>
				<li><a href="profile.php">Profile Page</a></li><br />
				<li><a href="photo_upload.php">Upload Photo</a></li><br />
				<li><a href="#"></a>Friends</li><br />
			</ul>
		</div>	
		<br><br>
		<div class="pagination grid_12">
			<?php				
			
				if($pagination->total_pages() > 1){				
					if($pagination->has_previous_page()){
						echo " <a href=\"manage_photo.php?page=";
						echo $pagination->previous_page();
						echo "\">&laquo; Prev </a>&nbsp;&nbsp;";
					}
					
					for($i=1; $i <= $pagination->total_pages(); $i++){
						if($i == $page){
							echo "<span class=\"selected\">{$i}</span> ";
						}else{
							echo "<a href=\"manage_photo.php?page={$i}";
							echo "\">{$i}</a>&nbsp;&nbsp;";
						}
					}
					
					if($pagination->has_next_page()){
						echo " <a href=\"manage_photo.php?page=";
						echo $pagination->next_page();
						echo "\">Next &raquo;</a>";
					}				
				}
			?>
		</div>				
		<div id="footer" class="grid_12"><p>Copyright <?php echo date("Y", time()); ?>, Alan DeLonga</p></div>
	</div>
  </body>
</html>

