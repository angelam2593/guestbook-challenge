<?php include('server.php'); ?>
<h3>Edit message</h3>	
  <?php
  	$messageId = $_POST['messageId'];

  	 echo  "<form action='".e(editMessage($db))."' method='POST'>
	  	 		<textarea name='messageBody' rows='10' cols=59 placeholder='".e(getMessageBody($db, $messageId))."'></textarea><br>
                <input name='messageId' type='hidden' value=".e($_POST["messageId"]).">
		 		<input type='submit' name='editMessage' value='Enter'>
		 		<input type='reset' name='reset' value='Clear Text'>
		 	</form>";
  ?>