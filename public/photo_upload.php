<?php
require_once('../includes/initialize.php');

if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php
	// checks to see if user has photos to be displayed
	$no_photos = true;
	
	$max_file_size = 1048576; // in bytes
							  // 10240 = 10 KB, 1048576 = 1 MB
	
	/* set up everything to show last uploaded photo
	$last_photo = new Photograph();						  
	$photo_attr = Photograph::last_uploaded_photo($session->user_id);
	$last_photo->filename = $photo_attr[0];
	*/
	
	// get the index for the last uploaded photo by user	
	$offset = (int)Photograph::count_all_for_user($session->user_id);
	$offset -= 1;		

	if(Photograph::count_all_for_user($session->user_id) > 0){
		$no_photos = false;
		// push the photograph object into the array as first element
		// access through $last_photo[0]
		$sql = "SELECT * FROM photographs ";
		$sql .= "WHERE uid=".$session->user_id;
		$sql .= " LIMIT 1 ";
		$sql .= "OFFSET {$offset}";
		$last_photo = Photograph::find_by_sql($sql);	
	}
	
	if(isset($_POST['submit'])){
		$photo = new Photograph();
		$photo->caption = $_POST['caption'];
		$photo->public = $_POST['access'];
		$photo->cat_id = $_POST['category'];
		$photo->rate = $_POST['rate'];
		$photo->download = $_POST['download'];
		$photo->uid = $session->user_id;
		$photo->attach_file($_FILES['file_upload']);	
		
		if($photo->save()){
			// Success
			$session->message("{$photo->filename} uploaded successfully");
			// calls header function needs to be in output buffering potentially
			$user = User::find_by_id($session->user_id);
			//$session->message("{$photo->filename} was uploaded");		
			log_action("\"{$user->user_name}\" uploaded", " \"{$photo->filename}\"");
			redirect_to('profile.php');
		} else {
			// Failure
			$message = join("<br />", $photo->errors);
		}
	}
?>
<?php include("layouts/public_header.php"); ?>
		<div id="title" class="grid_12">
			<?php if(!$no_photos){ ?>
				<h2>Your Last Uploaded Photo</h2>
			<?php } else {?>
				<h2>Uploade Your First Photo</h2>
			<?php }?>
			<div id="flash_message" class="grid_12 rounded1" <?php
	    	if(isset($message) && $message != ''){
	    		echo "style='display: block'";
	    	} else {
	    		echo "style='display: none'";
	    	} ?>">
	    	<?php echo $message; ?></div>
		</div>
		<div id="main" class="grid_12">

			<div align="center" class="up_photo_container grid_12">	
				<?php if(!$no_photos){ ?>	
				<h4><?php echo $last_photo[0]->caption;?></h4>					
				<img src="<?php echo $last_photo[0]->image_path();?>"/>				
				<?php }?>
			</div>
			<div class="grid_12 up_form">
				<h4>Upload A New Photo</h4>
				<form action="photo_upload.php" enctype="multipart/form-data" method="POST">
					<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size; ?>" />
					<p><input type="file" name="file_upload" /></p>
					<p>Allow Download: 
					<input type="radio" name="download" value="1" checked="checked" />Available
					<input type="radio" name="download" value="0" />No Downloads
					</p>
					<p>Access: <input type="radio" name="access" value="1" checked="checked"/>Public
					<input type="radio" name="access" value="0"/>Private
					<input type="radio" name="access" value="2"/>Friends
					</p>
					<p>Allow rating: <input type="radio" name="rate" value="1" checked="checked"/>Rate
					<input type="radio" name="rate" value="0"/>Don't Rate
					</p>
					<p>Caption: <input type="text" name="caption" value="" /></p>					
					<p>Category: <select name="category">
						<?php 
							$categories = Category::find_all();
							foreach ($categories as $category){ ?>
								<option value="<?php echo $category->id?>">
									<?php echo $category->name ?>
								</option>
						<?php } ?>
					</select></p>
					<input type="submit" name="submit" value="Upload" />
				</form>	
			</div>
		</div>
    	<div id="footer" class="grid_12">Copyright <?php echo date("Y", time()); ?>, Alan DeLonga</div>
    </div>
  </body>
</html>

