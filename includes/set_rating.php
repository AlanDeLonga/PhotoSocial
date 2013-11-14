<?php
	// This file is called by the ratings.js function set_rating
	// echo output is used by to update the current rating of the 
	// photo after it has been saved in the database
	
	require_once('initialize.php');
	// create new photo rating and set up attributes

	if(isset($session->user_id)){
		$pr = new PhotoRating( $_GET['pid'], $session->user_id, $_GET['rating']);
	} else {
		$pr = new PhotoRating( $_GET['pid'], NULL, $_GET['rating']);
	}
	
	// save the rating in photo_ratings table
	// and the rating in the photograph table
	$pr->save_rating_update_photo();
	
	echo Photograph::find_by_id($_GET['pid'])->rating;	
?>
