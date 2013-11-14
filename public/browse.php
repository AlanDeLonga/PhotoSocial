<?php
require_once('../includes/initialize.php');
?>
<?php

	// the current page number
	$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
	
	// records per page
	$per_page = 6;
	
	// total record count
	if(isset($_GET['cat'])){
		$_SESSION['category'] = $_GET['cat'];
		$cat_id = $_GET['cat'];
		$total_count = Photograph::count_all_for_category($_GET['cat']);
	} else {
		unset($_SESSION['category']);
		$total_count = Photograph::count_all_public();
	}
	
	// Find subset of photos
	$pagination = new Pagination($page, $per_page, $total_count);
	
	// Instead of finding all records , just find the records for this page
	$sql = "SELECT * FROM photographs ";
	$sql .= "WHERE public=1";
	if(isset($_GET['cat'])){
		$sql .= " AND cat_id=".$_GET['cat'];
	}
	$sql .= " LIMIT {$per_page} ";
	$sql .= "OFFSET {$pagination->offset()}";
	$photos = array_reverse(Photograph::find_by_sql($sql));	

	// keep track of current page by adding to links ?page=$page
	// or storing $page in the $session for when you navigate from this page

?>
<script type="text/javascript" src="javascript/ratings.js"></script>
<script type="text/javascript" src="javascript/ajax_helper.js"></script>
<?php include("layouts/public_header.php"); ?>
		<div id="title" class="browse_title" class="grid_12"> 
			<h2>Browse<?php if(isset($_GET['cat']) && $_GET['cat'] != 0){ ?>
				: <?php $cat = Category::get_cat_name($_GET['cat']);
				echo $cat;?>
				<?php } else { ?>					
					: All
				<?php } ?>
			</h2>
			<?php echo output_message($message); ?>
		</div>
		<div class="pagination grid_12" >
			<?php				
			
				if($pagination->total_pages() > 1){				
					if($pagination->has_previous_page()){
						echo " <a href=\"browse.php?page=";
						echo $pagination->previous_page();
						if(isset($_SESSION['category'])){
							echo "&cat=".$_SESSION['category'];
						}
						echo "\">&laquo; Prev </a>&nbsp;&nbsp;";
					}
					
					for($i=1; $i <= $pagination->total_pages(); $i++){
						if($i == $page){
							echo "<span class=\"selected\">{$i}</span> ";
						}else{
							echo "<a href=\"browse.php?page={$i}";
							if(isset($_SESSION['category'])){
								echo "&cat=".$_SESSION['category'];
							}
							echo "\">{$i}</a>&nbsp;&nbsp;";
						}
					}
					
					if($pagination->has_next_page()){
						echo " <a href=\"browse.php?page=";
						echo $pagination->next_page();
						if(isset($_SESSION['category'])){
							echo "&cat=".$_SESSION['category'];
						}
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
						<?php 
						if($photo->rate == 1){
							for($i=1+($count*10); $i<(11*($count+1)-$count); $i++){
						?>
						<a id="rating_star<?php echo $i ?>" class="rating_star_link" href="#" 
						onmouseout="browse_stars_current_rating(<?php echo $photo->rating.", ".$count?>);" 
						onmousedown="browse_set_rating(<?php echo $photo->id.", ".$i ?>);">
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
						</a>
						<?php } 
						} else { echo "<br>";}?>
			

					<a href="photo.php?id=<?php 
						$addy = $photo->id."&cat="; 
						if(isset($_SESSION['category'])){
							$addy .= $_SESSION['category'];
						} else {
							$addy .= '0';
						}
						echo $addy;
						?>
					">
					<img class="photo_display" src="<?php echo $photo->image_path();?>"/>
					</a>
					<p class="img_label"><?php echo $photo->caption;?></p>
				</div>
			<?php $count++; } ?>
		</div>
		<div id="category_nav" class="grid_3 rounded">
			<h2>Categories</h2>
			<ul>
				<?php if(isset($_GET['cat'])){?>
				<li><a href="browse.php">All</a></li><br />
				<?php } else {?>
				<li>All</li><br />	
			<?php 
				}
			 	$categories = Category::find_all();
				foreach ($categories as $category){ 
					if(isset($_GET['cat']) && $category->id == $_GET['cat']) {?>
					<li><?php echo $category->name; ?></li><br/>
					<?php } else {?>
					<li><a href="browse.php?cat=<?php echo $category->id; ?>">
			  			<?php echo $category->name; ?></a>
			 		</li><br/>
			<?php  } 
				} ?>
			</ul>
		</div>		
		<br><br>
		<div class="pagination grid_12">
			<p>
			<?php				
			
				if($pagination->total_pages() > 1){				
					if($pagination->has_previous_page()){
						echo " <a href=\"browse.php?page=";
						echo $pagination->previous_page();
						if(isset($_SESSION['category'])){
							echo "&cat=".$_SESSION['category'];
						}
						echo "\">&laquo; Prev </a>&nbsp;&nbsp;";
					}
					
					for($i=1; $i <= $pagination->total_pages(); $i++){
						if($i == $page){
							echo "<span class=\"selected\">{$i}</span> ";
						}else{
							echo "<a href=\"browse.php?page={$i}";
							if(isset($_SESSION['category'])){
								echo "&cat=".$_SESSION['category'];
							}
							echo "\">{$i}</a>&nbsp;&nbsp;";
						}
					}
					
					if($pagination->has_next_page()){
						echo " <a href=\"browse.php?page=";
						echo $pagination->next_page();
						if(isset($_SESSION['category'])){
							echo "&cat=".$_SESSION['category'];
						}
						echo "\">Next &raquo;</a>";
					}				
				}
			?>
			</p>
		</div>				
		<div id="footer" class="grid_12"><p>Copyright <?php echo date("Y", time()); ?>, Alan DeLonga</p></div>
	</div>
  </body>
</html>

