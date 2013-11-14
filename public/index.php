<?php
require_once('../includes/initialize.php');
?>
<?php

	// get images for explore section 2x7 (14 images)
	$sql = "SELECT * FROM photographs ";
	$sql .= "WHERE public=1";
	$sql .= " LIMIT 15 ";	
	$offset = Photograph::count_all_public();
	if($offset > 14){
		$offset -= 14;
		$sql .= "OFFSET {$offset}";
	}
	$photos = array_reverse(Photograph::find_by_sql($sql));	

?>
<?php include("layouts/public_header.php"); ?>
     <div id="main" class="grid_12">
 		<div id="main_photo_container">			
		<h3>Welcome to PhotoSocial!</h3>
		</div>
		<br><br>
		<div id="upload_title" class="grid_4 title_boxes rounded">
			<h3>Upload</h3>
			<p>Sign Up and keep track of all your photos in one place
				without ever having to worry about losing them. This
				server has unlimited capacity to store important photos
				you just can't risk losing if your computer crashes.</p>
		</div>
		<div id="share_title" class="grid_3 title_boxes rounded">
			<h3>Share</h3>
			<p>Open your photos to public view, share with friends or
				just upload for safe keeping. Privacy settings
				allow you to decide who has access to your photos.</p>
		</div>
		<div id="connect_title" class="grid_4 title_boxes rounded">
			<h3>Connect</h3>
			<p>Comment, rate, and keep up to-date on the photos
				friends and others are adding to the site. Comments 
				can only be posted by users, but anyone can browse 
				and rate publicly accessible photos.</p>
		</div>
		<div class="line">
			<hr />
		</div>
		<div id="signup_plug" class="grid_10 rounded">
			<div class="signup">
				<a href="signup.php"><img src="images/assets/signup_button.png" height="80px"></img></a> 
			</div>			
			<p>	It's Simple, Fast, Safe and FREE (and always will be). 
				<br>In a matter of seconds you could be uploading to your personalized account!
				<br>**New features are constantly being added to improve user experience.
			</p>			

		</div>  
		<div class="line">
			<hr />
		</div>
		<div id="explore_title" class="grid_3 title_boxes rounded">
			<h3>Explore</h3>
			<p>Keep up to date and check out your online community's most recently uploaded photos.<br>
			   <span style="color:red;">**Click the images to be directed to their display page.</span></p>
		</div>
		<div id="explore_imgs" class="grid_8">
			<?php 
			$count = 0;
			foreach($photos as $photo){?>
			<div id="ex_img<?php echo $count?>" class="explore_img_div rounded1"
				style="  background: transparent url(<?php echo $photo->image_path();?>) center center no-repeat;"
				onmousedown="window.location = 'photo.php?id=<?php echo $photo->id; ?>'">
			</div>
			<?php $count++;}?>
		</div>


	</div>
    <div id="footer" class="grid_12">Copyright <?php echo date("Y", time()); ?>, Alan DeLonga</div>
    </div>
  </body>
</html>
