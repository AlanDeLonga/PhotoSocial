<?php
require_once('../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php
	
	// get user from db to display up account information
	$user = User::find_by_id($session->user_id);
	
	// total record count
	$total_count = Photograph::count_all_for_user($session->user_id);
	$offset = 0;
	
	// set up query to get last 3 uploaded photos
	if($total_count > 3){ $offset = $total_count - 3;}
	$sql = "SELECT * FROM photographs ";
	$sql .= "WHERE uid=".$session->user_id;
	$sql .= " LIMIT 3 ";
	$sql .= "OFFSET {$offset} ";
	$photos = Photograph::find_by_sql($sql);		

	$t_photo = new Photograph();
	$top_photo = $t_photo->top_rated_photo($session->user_id);
?>
<script type="text/javascript" src="javascript/ratings.js"></script>
<?php include("layouts/public_header.php"); ?>
		
		<div id="title" class="grid_12">
			<h2>Recently Added Photos</h2>
		</div>

		<div id="title_image_bar" class="grid_12">
			<div id="flash_message" class="grid_12 rounded1 prof_msg" 
			<?php
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
			<?php 
			// fix for if user doesn't have enough images to display
			if($total_count > 0){
			$count = 0;
			foreach($photos as $photo){?>
				<div id="title_image<?php echo $count?>" class="title_images">
					<a href="photo.php?id=<?php echo $photo->id; ?>">
					<img class="photo_display" src="<?php echo $photo->image_path();?>"/>
					</a>	
				</div>
			<?php  
			$count++; }
			} ?>
		</div>	
		<div id="photo_container" class="grid_9 rounded">			
			<div class="top_photo grid_9">	
				<?php if($total_count > 0){	?>
				<h3>Your Top Photo</h3>		

				<p class="caption"><?php echo $top_photo[0]->caption;?></p>
				<div id="rating" class="top_rating">
					<?php 
						for($i=1; $i<11; $i++){
					?>					
					<img id="star<?php echo $i ?>" class="rating_star" src=
					<?php 
						// keeps stars always set at rating for photo when not being rated
						if($top_photo[0]->rating >= $i){ 
							echo "images/assets/rating_star.png";
						} else {
							echo "images/assets/rating_star_off.png";
						}
					?>
					/>
					</a>
					<?php } ?>		
				</div>
				<div id="top_rated_photo">
					<a href="photo.php?id=<?php echo $top_photo[0]->id; ?>">
					<img class="top_pic" src="<?php echo $top_photo[0]->image_path();?>"/>
					</a>		
				</div>		
				<?php }else { ?>
					<h3>You Have No Photos Uploaded Yet</h3>		
				<?php } ?>
						
			</div>
		</div>
		<div id="profile_img_container" class="grid_3 rounded">
			<h2><?php echo $user->user_name?></h2>
			
			<img src="images/assets/defaultProfile.jpg"></img>
		</div>	
		<div id="category_nav" class="grid_3 rounded">
			<h2>Account Options</h2>
			<ul>
				<li><a href="photo_upload.php">Upload Photo</a></li><br />
				<li><a href="manage_photo.php">Manage Photos</a></li><br />
				<li><a href="#"></a>Friends</li><br />
			</ul>
		</div>	
		<br><br>				
		<div id="footer" class="grid_12"><p>Copyright <?php echo date("Y", time()); ?>, Alan DeLonga</p></div>
	</div>
  </body>
</html>

