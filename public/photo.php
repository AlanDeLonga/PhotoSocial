<?php require_once('../includes/initialize.php'); ?>
<?php
	if(empty($_GET['id'])){
		$session->message("No Photo id was provided to display the image");
		// calls header function needs to be in output buffering potentially
		redirect_to('index.php');	
	}
		
	$photo = Photograph::find_by_id($_GET['id']);	
	if(!$photo){
		// Success
		$session->message("The photo could not be located");
		// calls header function needs to be in output buffering potentially
		redirect_to('index.php');
	}

	if(isset($_POST['submit'])){
		$author = trim($_POST['author']);
		$body = trim($_POST['body']);
		// Use static comment function to construct new comment obj
		$new_comment = Comment::make($photo->id, $author, $body);
		// attempt to save new comment into database
		if($new_comment && $new_comment->save()){
			// Comment saved
			// No message because comment will be displayed if saved
			// To make sure the form isnt resubmitted with the same data
			// if the page is reloaded, we redirect to clear the POST var
			if($session->user_id){
				$user = User::find_by_id($session->user_id);
			} else {
				$user->user_name = $author;
			}		
			
			$photo = Photograph::find_by_id($new_comment->photo_id);
			log_action("\"{$user->user_name}\" commented on", " \"{$photo->filename}\"");
			redirect_to("photo.php?id={$photo->id}");
		} else {
			// Failure
			if(!$author){
				$message = "You must fill in your name to post a comment";
			} elseif (!$body){
				$message = "You must add a comment to post a comment";
			} else {
				$message = "There was an error that prevented '{$author}' from posting '{$body}'";
			}
		}
	} else {
		$author = "";
		$body = "";
	}
	
	// Find all comments to be displayed
	$comments = $photo->comments();
	
	// for next and previous links
	// next		
	$sql = "SELECT * ";
	$sql .= "FROM photographs p";
	$sql .= " WHERE public=1 AND p.id < ".$photo->id;
	if(isset($_GET['cat']) && $_GET['cat'] != 0){
		$sql .= " AND cat_id = ".$_GET['cat'];
	}
	$sql .= " ORDER BY id DESC LIMIT 1";		
	$next_photo = Photograph::find_by_sql($sql);
	
	// previous
	$sql = "SELECT * ";
	$sql .= "FROM photographs p";
	$sql .= " WHERE public=1 AND p.id > ".$photo->id;
	if(isset($_GET['cat']) && $_GET['cat'] != 0){
		$sql .= " AND cat_id = ".$_GET['cat'];
	}
	$sql .= " LIMIT 1";	
	$prev_photo = Photograph::find_by_sql($sql);
?>
<script type="text/javascript" src="javascript/ratings.js"></script>
<?php include("layouts/public_header.php"); ?>
<script language="JavaScript" type="text/javascript">
	// initially set current_rating of photo
	current_rating = <?php echo $photo->rating ?>;
</script>
    <div id="main" class="grid_12">
    	<div id="message" class="grid_12"></div>
    	<div id="photo_nav" class="grid_12"> 
    		<?php 
    		//sets up previous link if one exists
    		if(isset($prev_photo[0])){ ?>
    		<a id="prev_link" href="photo.php?id=<?php 
				$addy = $prev_photo[0]->id."&cat="; 
				if(isset($_GET['cat'] )){
					$addy .= $_GET['cat'] ;
				} else {
					$addy .= '0';
				}
				echo $addy;
				?>">&laquo;Prev</a>
			<?php } 
				// if no previous exists set up back link
				else {?>
				<a id="prev_link" href="browse.php?<?php 
				if(isset($_GET['cat'] ) && $_GET['cat'] != 0 ){
					echo "cat=".$_GET['cat'] ;
				} 
				?>">&laquo;Back</a>
			<?php }	?>
			<?php 
			//sets up link link if one exists
			if(isset($next_photo[0])){ ?>
				<a id="next_link"  href="photo.php?id=<?php 
				$addy = $next_photo[0]->id."&cat="; 
				if(isset($_GET['cat'] )){
					$addy .= $_GET['cat'] ;
				} else {
					$addy .= '0';
				}
				echo $addy;
				?>">Next&raquo;</a>
			<?php } 
			// if no previous exists set up back link
				else {?>
				<a id="next_link" href="browse.php?<?php 
				if(isset($_GET['cat']) && $_GET['cat'] != 0 ){
					echo "cat=".$_GET['cat'] ;
				} 
				?>">Back&raquo;</a>
			<?php }	?>	
    	</div>
		<center><h2><?php echo $photo->caption ?></h2></center>
		<div id="rating" class="rating">
			<?php 
			if($photo->rate == 1){
				for($i=1; $i<11; $i++){
			?>
			<a id="rating_star<?php echo $i ?>" class="rating_star_link" href="#" onmouseover="decide_rating(<?php echo $i ?>);" 
			onmouseout="stars_current_rating();" onmousedown="set_rating(<?php echo $photo->id.", ".$i ?>);">
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
			</a>
			<?php }
			} ?>

		</div>
		<div id="large_photo" align="center">			
			<img src="<?php echo $photo->image_path();?>"/>
		</div>
		<!-- download image button -->
		<?php if($photo->download == 1){?>
		<form class="grid_12" style="text-align: center;" action="../includes/download.php" method="post">
			<input type="hidden" name="id" value="<?php echo $photo->id; ?>" />
			<input type="hidden" name="img" value="<?php echo SITE_ROOT ?>/public/images/<?php echo $photo->filename; ?>" />
			<?php if(isset($_GET['cat'])){?>
				<input type="hidden" name="cat" value="<?php echo $_GET['cat']; ?>" />
			<?php } ?>	
			<div id="download"><input type="submit" name="submit" value="Download Photo" /></div>
		</form>
		<?php }?>
		<br><br>
		<!-- List Comments-->
		<div class="photo_comments grid_12">
			<?php foreach($comments as $comment):?>
				<div class="comment">
					<div class="author">
						<?php echo htmlentities($comment->author); ?> wrote:
					</div>
					<div class="body">
						<?php echo strip_tags($comment->body, '<strong><em><p>'); ?>
					</div>
					<div class="meta-info" style="font-size: 0.8em;">
						<?php echo datetime_to_text($comment->created); ?>
					</div>
				</div>
			<?php endforeach; ?>
			<h5><?php if(empty($comments)) {echo "Be the first to Comment.";} ?></h5>
		</div>
		
		<!-- Form for Comment -->
		<?php if(isset($session->user_id)){
			$user = User::find_by_id($session->user_id);?>
		<div id="comment-form" class="grid_12">
			<?php echo output_message($message); ?>
			<form action="photo.php?id=<?php echo $photo->id; ?>" method="post">
				<input type="hidden" name="author" value="<?php echo $user->user_name; ?>" />
				<table>
					<tr>
						<td>Comment:</td>
						<td><textarea name="body" cols="40" rows="8"><?php echo $body; ?></textarea></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="submit" name="submit" value="Submit Comment" /></td>
					</tr>			
					
				</table>
			</form>
		</div>
		<?php }else {?>
		<div id="comment-form" class="grid_12">
			<center><h4>You must <a href="login.php">log in</a> to comment</h4></center>
		</div>
		<?php }?>
		
	</div>
		
    <div id="footer" class="grid_12">Copyright <?php echo date("Y", time()); ?>, Alan DeLonga</div>
    </div>
  </body>
</html>

