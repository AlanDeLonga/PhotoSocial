function validateForm() 
{
	var okSoFar=true
	with (document.phpformmailer)
	{
		var foundAt = email.value.indexOf("@",0)
		if (foundAt < 1 && okSoFar)
  		{
    			okSoFar = false
    			alert ("Please enter a valid email address.")
    			email.focus()
  		}
  		var e1 = email.value
  		var e2 = email2.value
  		if (!(e1==e2) && okSoFar){
    
    		okSoFar = false
    		alert ("Email addresses you entered do not match.  Please re-enter.")
    		email.focus()
  		}
  		if (thesubject.value=="" && okSoFar){
    
    		okSoFar=false
    		alert("Please enter the subject.")
    		thesubject.focus()
  		}
  		if (themessage.value=="" && okSoFar){
    
    		okSoFar=false
    		alert("Please enter the details of your message.")
    		themessage.focus()
  		}
  		if (okSoFar==true) {
   			block_spam_bots.value=4*3;//spam bots currently can not read JavaScript, if could then they'd fail the math!
   			submit();                  
  		} 
 	}
}