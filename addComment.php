<?php include('server.php'); ?>
<h3>Add comment</h3>	
  <?php
  	$message_id = $_POST['messageId'];
  	$user_id = $_POST['userId'];

  	echo    "<form action='".e(addComment($db))."' method='POST'>
	  	 		       <textarea name='commentBody' rows='10' cols=59'></textarea><br>
	  	 		       <input name='userId' type='hidden' value=".e($user_id).">
                 <input name='messageId' type='hidden' value=".e($message_id).">
		 		         <input type='submit' name='addComment' value='Enter'>
		 		         <input type='reset' name='reset' value='Clear Text'>
		      	</form>";
  ?>