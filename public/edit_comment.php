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
		// Success
		$session->message("The photo could not be located");
		// calls header function needs to be in output buffering potentially
		redirect_to('manage_photo.php');
	}
	
	// Find all comments to be displayed
	$comments = $photo->comments();

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
			
			<div id="photo_nav" class="grid_12"><a id="back_link" href="manage_photo.php"> &laquo; Back </a><br />
			</div>
			<center><h2><?php echo $photo->caption ?></h2></center>
			<div align="center">			
				<img src="<?php echo $photo->image_path();?>"/>
			</div>
			<br><br>
			<!-- List comments for edit-->
			<div class="comments grid_12">
				<?php foreach($comments as $comment):?>
					<div class="edit_comment">
						<div class="author">
							<?php echo htmlentities($comment->author); ?> wrote:
						</div>
						<div class="body">
							<?php echo strip_tags($comment->body, '<strong><em><p>'); ?>
						</div>
						<div class="meta-info" style="font-size: 0.8em;">
							<?php echo datetime_to_text($comment->created); ?>
						</div>
						<a href="delete_comment.php?id=<?php echo $comment->id."&pid=".$photo->id;?>">Delete</a>
					</div>
				<?php endforeach; ?>				
				<h5><?php if(empty($comments)) {echo "No Comments.";} ?></h5>
			</div>
		</div>
    	<div id="footer" class="grid_12">Copyright <?php echo date("Y", time()); ?>, Alan DeLonga</div>
    </div>
  </body>
</html>
