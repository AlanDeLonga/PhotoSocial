<?php
require_once('initialize.php');

/* PHP Form Mailer - easy, secure form mail:

      Should work fine on most Unix/Linux platforms
      for a Windows version see: asp.thedemosite.co.uk
*/

// ------- three variables you MUST change below  -------------------------------------------------------
$replyemail="cscatcalpoly@yahoo.com"; //change to your email address
$valid_ref1="http://frompointatobeyond.com/photo_social/public/contact.php"; //change to the path of the file calling it
$valid_ref2="http://www.frompointatobeyond.com/photo_social/public/contact.php"; //change to the path of the file calling it

// -------- No changes required below here -------------------------------------------------------------
//
// email variable not set - load $valid_ref1 page
if (!isset($_POST['email']))
{
 echo "<script language=\"JavaScript\"><!--\n ";
 echo "top.location.href = \"$valid_ref1\"; \n// --></script>";
 exit;
}
$ref_page = $_SERVER["HTTP_REFERER"];
$valid_referrer = 0;
if($ref_page==$valid_ref1) $valid_referrer=1;
elseif($ref_page==$valid_ref2) $valid_referrer=1;
if((!$valid_referrer) OR ($_POST["block_spam_bots"]!=12))//you can change this but remember to change it in the contact form too
{
	$session->mail_message = 'ERROR - message not sent.';
	redirect_to('../public/contact.php');
 /*
 if (file_exists("debug.flag")) echo '<hr>"$valid_ref1" and "$valid_ref2" are incorrect within the file:<br>
                                      contact_process.php <br><br>On your system these should be set to: <blockquote>
                                                                          $valid_ref1="'.str_replace("www.","",$ref_page).'"; <br>
                                                                          $valid_ref2="'.$ref_page.'";
                                                                          </blockquote></h2>Copy and paste the two lines above
                                                                          into the file: contact_process.php <br> (replacing the existing variables and settings)';
 exit;

 */
}

//check user input for possible header injection attempts!
function is_forbidden($str,$check_all_patterns = true)
{
 $patterns[0] = '/content-type:/';
 $patterns[1] = '/mime-version/';
 $patterns[2] = '/multipart/';
 $patterns[3] = '/Content-Transfer-Encoding/';
 $patterns[4] = '/to:/';
 $patterns[5] = '/cc:/';
 $patterns[6] = '/bcc:/';
 $forbidden = 0;
 for ($i=0; $i<count($patterns); $i++)
  {
   $forbidden = preg_match($patterns[$i], strtolower($str));
   if ($forbidden) break;
  }
 //check for line breaks if checking all patterns
 if ($check_all_patterns AND !$forbidden) $forbidden = preg_match("/(%0a|%0d|\\n+|\\r+)/i", $str);
 if ($forbidden)
 {
  echo "<font color=red><center><h3>STOP! Message not sent.</font></h3><br><b>
        The text you entered is forbidden, it includes one or more of the following:
        <br><textarea rows=9 cols=25>";
  foreach ($patterns as $key => $value) echo trim($value,"/")."\n";
  echo "\\n\n\\r</textarea><br>Click back on your browser, remove the above characters and try again.";
  exit();
 }
}

foreach ($_REQUEST as $key => $value) //check all input
{
 if ($key == "themessage") is_forbidden($value, false); //check input except for line breaks
 else is_forbidden($value);//check all
}

$name = $_POST["name"];
$email = $_POST["email"];
$thesubject = $_POST["thesubject"];
$themessage = $_POST["themessage"];

$success_sent_msg="Your message has been successfully sent to me, and I will reply as soon as possible.\n
                   A copy of your message has been sent to you, thank you for contacting me.";

$replymessage = "Hi $name

I appreciate your interest, and will respond to your message as soon as possible.

Please DO NOT reply to this email. This is an automated response.

Below is a copy of the message you submitted:
--------------------------------------------------
Subject: $thesubject

Message:

$themessage
--------------------------------------------------

";

$themessage = "name: $name \nMessage:\n $themessage";
mail("$replyemail",
     "$thesubject",
     "$themessage",
     "From: $email\n\nReply-To: $email");
mail("$email",
     "Receipt: $thesubject \n",
     "$replymessage",
     "From: $replyemail\n\nReply-To: $replyemail");
//echo $success_sent_msg;
// save success message to be displayed after redirection

// send the user back to their profile if they are logged in
// and the the home page if they are not
if(isset($session->user_id)){
	redirect_to('../public/profile.php');
} else {
	redirect_to('../public/index.php');
}
?>