	
// File contains functions needed for setting up and handeling 
// ratings stars for photos	
	
	var current_rating = 0;
	
	// Makes stars light up, up to where you have your cursor pointing
	// pass in current rating of star hovered over 
	function decide_rating( r ){
		for(var i=1; i<=10; i++){
			var star = document.getElementById("star"+i);
			if(i<=r){
				star.src = "images/assets/rating_star.png";
			} else {
				star.src = "images/assets/rating_star_off.png";
			}
		}
		return false;
	}
	
	// defaults the stars to light up with the current rating
	function stars_current_rating(){
		for(var i=1; i<=10; i++){
			var star = document.getElementById("star"+i);
			if(i<=current_rating){
				star.src = "images/assets/rating_star.png";
			} else {
				star.src = "images/assets/rating_star_off.png";
			}
		}
		return false;
	}
	
	function set_rating(pid, rating){
	
		if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} else {// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
	   		if(xmlhttp.readyState==4 && xmlhttp.status==200){
	   			// no message to be displayed
	    		//document.getElementById("message").innerHTML=xmlhttp.responseText;
	    		
	    		// for this to work ratings.js must be before this file when linked to
	    		// set_ratings is echoing the $photo->rating for the calling pid 
	    		// as the xmlhttp.responseText
	    		current_rating = xmlhttp.responseText;
	    		stars_current_rating();
	  		}
		}
		xmlhttp.open("GET","../includes/set_rating.php?pid="+pid+"&rating="+rating,true);
		xmlhttp.send();
		
	}
	
	/************** for ratings on browsed photos ********************/
	// eventually will combine logic with above functions
	
	
		// defaults the stars to light up with the current 
	// $photo->rating that is passed in as r
	// count is the offset to compute the indexes for the stars 
	function browse_stars_current_rating(r, count){
		for(var i=1+(count*10); i<(11*(count+1)-count); i++){
			var star = document.getElementById("star"+i);
			if(r+(count*10) >= (i)){
				star.src = "images/assets/rating_star.png";
			} else {
				star.src = "images/assets/rating_star_off.png";
			}
		}
		return false;
	}
	
	/************** opted against trying to add rating through browse *****
	// because of the difficulties of updating the rating after it is selected
	
	// Makes stars light up, up to where you have your cursor pointing
	// pass in current index rating of star hovered over, 
	// count is the offset to compute the indexes for the stars 
	function browse_decide_rating( r, count ){
		for(var i=1+(count*10); i<(11*(count+1)-count); i++){
			var star = document.getElementById("star"+i);
			if(r >= i){
				star.src = "images/assets/rating_star.png";
			} else {
				star.src = "images/assets/rating_star_off.png";
			}
		}
		return false;
	}
	

	function browse_set_rating(pid, rating){		
		// since rating is being passed in as the number of the
		// star that was selected we have to mod with 10 so we get
		// a number 1-9 if it is 0 then it was the 10th star
		rating = rating%10;
		if(rating == 0){ rating = 10;}


		if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} else {// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
	   		if(xmlhttp.readyState==4 && xmlhttp.status==200){
	   			// no message to be displayed
	    		//document.getElementById("message").innerHTML=xmlhttp.responseText;
	    		
	    		// for this to work ratings.js must be before this file when linked to
	    		// set_ratings is echoing the $photo->rating for the calling pid 
	    		// as the xmlhttp.responseText
	    		stars_current_rating(xmlhttp.responseText);
	  		}
		}
		xmlhttp.open("GET","../includes/set_rating.php?pid="+pid+"&rating="+rating,true);
		xmlhttp.send();
	}
*/