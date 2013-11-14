<?php
require_once('../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php
	if(empty($_GET['id'])){
		$session->message("No Photo id was provided to display the image");
		// calls header function needs to be in output buffering potentially
		redirect_to('manage_photo.php');	
	}
		
	$photo = Photograph::find_by_id($_GET['id']);	
	if(!$photo){		
 		// failure: image could not be found in database
		$session->message("The photo could not be located");
		// calls header function needs to be in output buffering potentially
		redirect_to('manage_photo.php');
	} else {
		//Success
		// If submit was set the photo needs to be updated
		if(isset($_POST['submit'])){
			
			$cap = $_POST['caption'];
			$perm = $_POST['access'];
			$cat = $_POST['category'];
			$rate = $_POST['rate'];
			$download = $_POST['download'];
			
			if($photo->update_photo($cap, $perm, $cat, $rate)){
				// Success
				$session->message("{$photo->filename} was updated successfully");
				// calls header function needs to be in output buffering potentially
				$user = User::find_by_id($session->user_id);
				//$session->message("{$photo->filename} was uploaded");		
				log_action("\"{$user->user_name}\" edited", " \"{$photo->filename}\"");
				$edited_path = "edit_photo.php?id=".$photo->id;
				redirect_to($edited_path);
			} else {
				// Failure
				$message = join("<br />", $photo->errors);
			}
		}
	}
	
 ?>

<?php include("layouts/public_header.php"); ?>
	    <div id="main" class="grid_12">
	    	<div id="flash_message" class="grid_12 rounded1" <?php
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
	    	<div id="photo_nav" class="grid_12"> <a id="back_link" href="manage_photo.php"> &laquo; Back </a><br />
	    	</div>
			<center><h2><?php echo $photo->caption ?></h2></center>
			<div id="rating" class="rating">
			<?php 
			if($photo->rate == 1){
				for($i=1; $i<11; $i++){
			?>
			<img id="star<?php echo $i ?>" class="rating_star" src=
			<?php 
				// Sets up stars with initial rating in db when page is first loaded
				if($photo->rating >= $i){ 
					echo "images/assets/rating_star.png";
				} else {
					echo "images/assets/rating_star_off.png";
				}
			?>
			/>
			<?php }
			} ?>

		</div>

			<div id="large_photo" align="center">			
				<img src="<?php echo $photo->image_path();?>"/>
			</div>
			<br><br>
			<!-- Form to update photo-->
			<div class="grid_12 ed_form">
				<form action="edit_photo.php?id=<?php echo $photo->id; ?>" enctype="multipart/form-data" method="POST">
					<p>Allow Download: <input type="radio" name="rate" value="1" <?php if($photo->download == 1){ ?>
						checked 
					<?php }?>/>Available
					<input type="radio" name="rate" value="0" <?php if($photo->download == 0){ ?>
						checked 
					<?php }?>/>No Downloads
					</p>
					<p>Access: &nbsp; 
					<input type="radio" name="access" value="1" <?php if($photo->public == 1){ ?>
						checked 
					<?php }?>/>Public
					<input type="radio" name="access" value="0" <?php if($photo->public == 0){ ?>
						checked 
					<?php }?>/>Private
					<input type="radio" name="access" value="2" <?php if($photo->public == 2){ ?>
						checked 
					<?php }?>/>Friends
					</p>
					<p>Allow rating: <input type="radio" name="rate" value="1" <?php if($photo->rate == 1){ ?>
						checked 
					<?php }?>/>Rate
					<input type="radio" name="rate" value="0" <?php if($photo->rate == 0){ ?>
						checked 
					<?php }?>/>Don't Rate
					</p>					
					<p>Caption: &nbsp;<input type="text" name="caption" value="<?php echo $photo->caption?>" /></p>					
					<p>Category: &nbsp;<select name="category">
						<?php 
							$categories = Category::find_all();
							foreach ($categories as $category){ ?>
								<option value="<?php echo $category->id?>"
									<?php if($photo->cat_id == $category->id){ ?>
										selected="selected"
									<?php }?>>
									<?php echo $category->name ?>
								</option>
						<?php } ?>
					</select></p>
					<input type="submit" name="submit" value="Save Edit" />
				</form>	
			</div>
		</div>
    	<div id="footer" class="grid_12">Copyright <?php echo date("Y", time()); ?>, Alan DeLonga</div>
    </div>
  </body>
</html>
