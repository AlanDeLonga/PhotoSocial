<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php
	if(empty($_GET['id'])){
		$session->message("No Photo id was provided to display the image");
		// calls header function needs to be in output buffering potentially
		redirect_to('show_photos.php');	
	}
		
	$photo = Photograph::find_by_id($_GET['id']);	
	if(!$photo){
		// Success
		$session->message("The photo could not be located");
		// calls header function needs to be in output buffering potentially
		redirect_to('show_photos.php');
	}
	
	// Find all comments to be displayed
	$comments = $photo->comments();

http://localhost/photo_gallery/public/admin/images/africa.jpg
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
		<center><h2><?php echo $photo->caption ?></h2></center>
			<a href="show_photos.php"> &laquo; Back </a><br /><br />
		<div align="center">			
			<img src="<?php echo $photo->admin_image_path();?>"/>
		</div>
		<br><br>
		<!-- List Comments-->
		<div class="comments">
			<?php foreach($comments as $comment):?>
				<div class="comment" style="margin-bottom; 2em;">
					<div class="author">
						<?php echo htmlentities($comment->author); ?> wrote:
					</div>
					<div class="body">
						<?php echo strip_tags($comment->body, '<strong><em><p>'); ?>
					</div>
					<div class="meta-info" style="font-size: 0.8em;">
						<?php echo datetime_to_text($comment->created); ?>
					</div>
					<a href="delete_comment.php?id=<?php echo $comment->id;?>">Delete</a>
				</div>
			<?php endforeach; ?>
			<?php if(empty($comments)) {echo "No Comments.";} ?>
		</div>
		
		
	</div>
		
    <div id="footer">Copyright <?php echo date("Y", time()); ?>, Alan DeLonga</div>
  </body>
</html>
