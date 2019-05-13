<?php include('server.php'); ?>
<h3>Are you use you want to delete this message?</h3>	
  <?php
  		echo "<form action='".deleteMessage($db)."' method='POST'>
                <input name='messageId' type='hidden' value=".e($_POST['messageId']).">
                <button type='submit' name='deleteMessage'>Delete this message</button>
             </form>";

?>