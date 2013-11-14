<?php	require_once('../includes/initialize.php'); ?>
<script type="text/javascript" src="javascript/validate_mail_form.js"></script>
<?php include("layouts/public_header.php"); ?>
	<div id="main" class="grid_12">
    		<div id="title" class="grid_12" >
				<h2>Contact Me</h2>
				<?php echo output_message($message); ?>
				<?php if(isset($session->mail_message)){echo $session->mail_message;} ?>
			</div>
			<div id="contact" class="grid_12">
			 <div id="contact_photo">
			 	<img src="images/assets/contact.png"></img>
			 </div>
			 <div id="contact_box" >
    			<form name="phpformmailer" action="../includes/contact_process.php" align="center" method="post">
				  <div align="center"><center><table class="rounded" width="742" cellspacing="6">
				    <tr>
				      <td width="162"><br></td>				      
				    </tr>
				    <tr>
				      <td align="right" width="162">Your name:</td>
				      <td width="556"><font face="Arial">
					  <input class="inputc" size="50" name="name" 
					  <?php
					  	if(isset($session->user_id)){
					  		$user = User::find_by_id($session->user_id);
							echo 'value="'.$user->full_name().'" readonly="readonly"';
					  	}
					  
					  ?>>
					  <input type="hidden" name="block_spam_bots" value="1">
				      </font></td>
				    </tr>
				    <tr>
				      <td align="right" width="162"><font color="#000080" size="1">*</font> Your email
				      address:</td>
				      <td align="left" width="556"><font face="Arial"><input class="inputc" size="50"
				      name="email">
				      </font></td>
				    </tr>
				    <tr align="middle">
				      <td align="right" width="162"><font color="#000080" size="1">*</font> Confirm email
				      address:</td>
				      <td width="556" align="left"><font face="Arial"><input class="inputc" size="50"
				      name="email2">
				      </font></td>
				    </tr>
				    <tr>
				      <td align="right" width="162"><font color="#000080" size="1">*</font> Subject:</td>
				      <td width="556"><font face="Arial"><input class="inputc" size="60" name="thesubject">
				      </font></td>
				    </tr>
				    <tr>
				      <td align="right" valign="top" width="162">&nbsp;
				        <p><font color="#000080" size="1">*</font> Your message:</td>
				      <td width="556"><textarea style="FONT-SIZE: 10pt" name="themessage" rows="7" cols="60"></textarea>
				      </td>
				    </tr>
				    <tr>
				      <td width="162"></td>
				      <td width="556">
				        <input type="button" class="button"
				      value="Send" name="B1" ONCLICK="javascript:validateForm()">
				          You must fill in the fields marked with a * <br><br>
				        </td>
				    </tr>
				  </table>
				      <p>&nbsp;</p>
				      <p>&nbsp;</p>
				      <p>&nbsp;</p>
				      <p>&nbsp;</p>
				      <p>&nbsp;</p>      
				  </center></div>
				</form>
		  	</div>
		  </div>
    	</div>
    	<div id="footer" class="grid_12">Copyright <?php echo date("Y", time()); ?>, Alan DeLonga</div>
    </div>
  </body>
</html>
