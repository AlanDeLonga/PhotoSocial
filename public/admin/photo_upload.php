<?php
require_once('../../includes/initialize.php');

if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php
	$max_file_size = 1048576; // in bytes
							  // 10240 = 10 KB, 1048576 = 1 MB

	if(isset($_POST['submit'])){
		$photo = new Photograph();
		$photo->caption = $_POST['caption'];
		$photo->attach_file($_FILES['file_upload']);	
		
		if($photo->save()){
			// Success
			$session->message("{$photo->filename} uploaded successfully");
			// calls header function needs to be in output buffering potentially
			$user = User::find_by_id($session->user_id);
			$session->message("{$photo->filename} was deleted");		
			log_action("\"{$user->user_name}\" uploaded", " \"{$photo->filename}\"");
			redirect_to('show_photos.php');
		} else {
			// Failure
			$message = join("<br />", $photo->errors);
		}
	}
?>
<?php  include_layout_template('admin_header.php'); ?>

<h2>Photo Upload</h2>

	<?php echo output_message($message); ?>
	<div class="nav_links">
		<ul>
			<li><a href="index.php">Home</a></li>
			<li><a href="show_photos.php">Show Photos</a></li>
			<li><a href="logfile.php">Logs</a></li>
		</ul>
	</div>
	<form action="photo_upload.php" enctype="multipart/form-data" method="POST">
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size; ?>" />
		<p><input type="file" name="file_upload" /></p>
		<p>Caption: <input type="text" name="caption" value="" /></p>
		<input type="submit" name="submit" value="Upload" />
	</form>
	
	
<?php  include_layout_template('admin_footer.php'); ?>

