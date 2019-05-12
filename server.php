<?php 
	session_start();
	error_reporting(E_ALL);

	$username = "";
	$email = "";
	$errors = array();

	$db = mysqli_connect('localhost', 'root', '', 'php_db') or die($db);

	$query_messages = "SELECT * FROM message";
	$result_message = mysqli_query($db, $query_messages);

	if (isset($_POST['register']))
	{
		$username = mysqli_real_escape_string($db, $_POST['username']);
		$email = mysqli_real_escape_string($db, $_POST['email']);
		$password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
		$password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

		$sql_check = "SELECT * FROM users WHERE email='$email'";
		$result = mysqli_query($db, $sql_check);
		$row = mysqli_fetch_assoc($result);;

		if($email == $row['email']){
			array_push($errors, "The user already exists!");
		}
		else{
			if(empty($username)){
				array_push($errors, "Missing username");
			}
			if(empty($email)){
				array_push($errors, "Missing email");
			}
			if(empty($password_1)){
				array_push($errors, "Missing password");
			}
			if($password_1 != $password_2){
				array_push($errors, "The passwords don't match");
			}
		}

		if(count($errors) == 0){
			$password = md5($password_1);
			$sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
			mysqli_query($db, $sql);

			$get_id_query = "SELECT id FROM users WHERE username='$username'";
			$result = mysqli_query($db, $get_id_query);
			$row = mysqli_fetch_assoc($result); 

			$_SESSION['username'] = $username;
			$_SESSION['user_id'] = $row['id'];
			$_SESSION['success'] = 'You are now logged in!';
			header('location: index.php');

		}
	}

	if (isset($_POST['login']))
	{
		$username = mysqli_real_escape_string($db, $_POST['username']);
		$password = mysqli_real_escape_string($db, $_POST['password']);
		$errors = array(); 

		if(empty($username)){
			array_push($errors, "Missing username");
		}
		if(empty($password)){
			array_push($errors, "Missing password");
		}

		if(count($errors) == 0){
			$password = md5($password);
			$query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
			$result = mysqli_query($db, $query); 
			$row = mysqli_fetch_assoc($result); 

			if (mysqli_num_rows($result) == 1){

				$_SESSION['username'] = $username;
				$_SESSION['user_id'] = $row['id'];
				$_SESSION['success'] = 'You are now logged in!';
				header('location: index.php');
			}
			else{

				array_push($errors, "The username or password doesn't match");
				header('location: login.php');
			}
		}
	}

	if (isset($_GET['logout']))
	{
		session_destroy();
		unset($_SESSION['username']);
		header('location: login.php');
	}

	if (isset($_POST['insertMessage']))
	{
		$body = mysqli_real_escape_string($db, $_POST['message']);
		$username = $_SESSION['username'];
		$query = "SELECT id FROM users WHERE username = '$username'";
		$result = mysqli_query($db, $query); 
		$row = mysqli_fetch_assoc($result);
		$user_id = $row['id']; 

		$query_final = "INSERT INTO message (user_id, body, created_at, updated_at) VALUES ('$user_id', '$body', now(), now())"; 
		mysqli_query($db, $query_final);

		$_SESSION['success'] = 'Your message was entered!';
		header('location: index.php');
	}

	function get_user_by($user_id)
	{
		$sql = "SELECT username FROM users WHERE id='$user_id'";
		$result = mysqli_query($GLOBALS['db'], $sql);
		$row = mysqli_fetch_assoc($result); 
		return $row['username'];
	}


	function editMessage($db)
	{
		if (isset($_POST['editMessage'])){

  			$messageId = $_POST['messageId'];
  			$body = mysqli_real_escape_string($db, $_POST['messageBody']); 
  			
  			$sql = "UPDATE message SET body='".$body."', updated_at=now() WHERE id=".$messageId."";
			mysqli_query($db, $sql);

			$_SESSION['success'] = 'Your message was edited!';
			header('location: index.php');
		}
	}

	function deleteMessage($db)
	{
		if (isset($_POST['deleteMessage'])){

  			$messageId = $_POST['messageId'];
  			
  			$sql = "DELETE FROM message WHERE id=".$messageId."";
			$db->query($sql);

			$_SESSION['success'] = 'Your message was deleted!';
			header('location: index.php');
		}
	}

	function getMessageBody($db, $messageId)
	{
		$sql = "SELECT body FROM message WHERE id=".$messageId."";
		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_assoc($result);
		return $row['body'];
	}

	function addComment($db)
	{
		if (isset($_POST['addComment'])){
			
			$user_id = $_POST['userId'];
			$message_id = $_POST['messageId'];
			$comment_body = mysqli_real_escape_string($db, $_POST['commentBody']);

			$sql = "INSERT INTO comment(user_id, message_id, body, created_at, updated_at)
					VALUES ('$user_id', '$message_id', '$comment_body', now(), now())";
			mysqli_query($db, $sql);

			$_SESSION['success'] = 'Your comment was entered!';
			header('location: index.php');
		}
	}

	function getAllComments($db, $id)
	{
		$sql = "SELECT * FROM comment WHERE message_id=".$id."";
		$result = mysqli_query($db, $sql);
		echo "<i>Comments:</i><ol>";	
		while($row = mysqli_fetch_assoc($result)){
			echo "<li>".$row['body']."</li>";
		}
		echo "</ol>";
	}


?>