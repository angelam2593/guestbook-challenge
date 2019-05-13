<?php 
	session_start();
	require_once 'Token.php';
	error_reporting(E_ALL);

	$username = "";
	$email = "";
	$errors = array();

	$db = mysqli_connect('localhost', 'root', '', 'php_db') or die($db);

	$query_messages = $db->prepare("SELECT * FROM message");
	$query_messages->execute();
	$result_message = $query_messages->get_result();

	if (isset($_POST['register']))
	{
		if(Token::check($_POST['token']))
		{
			$username = mysqli_real_escape_string($db, $_POST['username']);
			$email = mysqli_real_escape_string($db, $_POST['email']);
			$password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
			$password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

			$sql = $db->prepare("SELECT * FROM users WHERE email=?");
			$sql->bind_param("s", $email);
			$sql->execute();
			$result = $sql->get_result();
			$row = $result->fetch_assoc();

			if(empty($username) || empty($email) || empty($password_1) || empty($password_2))
			{
				if(empty($username))
					array_push($errors, "Missing username");
				if(empty($email))
					array_push($errors, "Missing email");
				if(empty($password_1))
					array_push($errors, "Missing password");
				if($password_1 != $password_2)
					array_push($errors, "The passwords don't match");
			}
			else if(validateEmail($email) == false && !empty($email))
					array_push($errors, "Invalid email");
			else if($email == $row['email']){
				array_push($errors, "The user already exists!");
			}

			if(count($errors) == 0){
				$password = password_hash($password_1, PASSWORD_DEFAULT);
				//$password = md5($password_1);
				
				$sql = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)"); 
				$sql->bind_param("sss", $username, $email, $password);
				$sql->execute();

				$get_id_query = $db->prepare("SELECT id FROM users WHERE username=?");
				$get_id_query->bind_param("s", $username);
				$get_id_query->execute();
				$result = $get_id_query->get_result();
				$row = $result->fetch_assoc();

				$_SESSION['username'] = $username;
				$_SESSION['user_id'] = $row['id'];
				$_SESSION['success'] = 'You are now logged in!';
				header('location: index.php');
			}
		}
	}

	if (isset($_POST['login']))
	{
		if(Token::check($_POST['token']))
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

			if(count($errors) == 0)
			{
				//$password = md5($password); //some of the users in my db are from md5 (id<16)
				$sql = $db->prepare("SELECT password FROM users WHERE username=?");
				$sql->bind_param("s", $username);
				$sql->execute();
				$result = $sql->get_result();
				$row = $result->fetch_assoc();

				if(password_verify($password, $row['password']))
				{
					$query = "SELECT * FROM users WHERE username = '$username'";
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
		if(Token::check($_POST['token']))
		{
			$body = mysqli_real_escape_string($db, $_POST['message']);
			$username = $_SESSION['username'];

			$sql = $db->prepare("SELECT id FROM users WHERE username =?");
			$sql->bind_param("s", $username);
			$sql->execute();
			$result = $sql->get_result();
			$row = $result->fetch_assoc();
			$user_id = $row['id']; 

			$query_final = $db->prepare("INSERT INTO message (user_id, body, created_at, updated_at) VALUES (?, ?, now(), now())"); 
			$query_final->bind_param("ss", $user_id, $body);
			$query_final->execute();

			$_SESSION['success'] = 'Your message was entered!';
			header('location: index.php');
		}
	}

	function get_user_by($db, $user_id)
	{
		$sql = $db->prepare("SELECT username FROM users WHERE id=?");
		$sql->bind_param("s", $user_id);
		$sql->execute();
		$result = $sql->get_result();
		$row = $result->fetch_assoc();

		return $row['username'];
	}


	function editMessage($db)
	{
		if (isset($_POST['editMessage']))
		{
  			$messageId = $_POST['messageId'];
  			$body = mysqli_real_escape_string($db, $_POST['messageBody']); 
  			
  			$sql = $db->prepare("UPDATE message SET body=?, updated_at=now() WHERE id=?");
  			$sql->bind_param("ss", $body, $messageId);
  			$sql->execute();

			$_SESSION['success'] = 'Your message was edited!';
			header('location: index.php');
		}
	}

	function deleteMessage($db)
	{
		if (isset($_POST['deleteMessage'])){

  			$messageId = $_POST['messageId'];
  			
  			$sql = $db->prepare("DELETE FROM message WHERE id=?");
  			$sql->bind_param("s", $messageId);
  			$sql->execute();

			$_SESSION['success'] = 'Your message was deleted!';
			header('location: index.php');
		}
	}

	function getMessageBody($db, $messageId)
	{
		$sql = $db->prepare("SELECT body FROM message WHERE id=?");
		$sql->bind_param("s", $messageId);
		$sql->execute();
		$result = $sql->get_result();
		$row = $result->fetch_assoc();

		return $row['body'];
	}

	function addComment($db)
	{
		if (isset($_POST['addComment']))
		{	
			$user_id = $_POST['userId'];
			$message_id = $_POST['messageId'];
			$comment_body = mysqli_real_escape_string($db, $_POST['commentBody']);

			$sql = $db->prepare("INSERT INTO comment(user_id, message_id, body, created_at, updated_at)
					VALUES (?, ?, ?, now(), now())");
			$sql->bind_param("sss", $user_id, $message_id, $comment_body);
			$sql->execute();

			$_SESSION['success'] = 'Your comment was entered!';
			header('location: index.php');
		}
	}

	function getAllComments($db, $id)
	{
		$sql = $db->prepare("SELECT * FROM comment WHERE message_id=?");
		$sql->bind_param("s", $id);
		$sql->execute();
		$result = $sql->get_result();

		echo "<i>Comments:</i><ol>";	
		while($row = $result->fetch_assoc()){
			echo "<li>".$row['body']." [".$row['created_at']."] by ".getUser($db, $row['user_id'])."</li>";
		}
		echo "</ol>";
	}

	function getUser($db, $user_id)
	{
		$sql = $db->prepare("SELECT username FROM users WHERE id=?");
		$sql->bind_param("s", $user_id);
		$sql->execute();
		$result = $sql->get_result();
		$row = $result->fetch_assoc();

		return $row['username'];
	}

	function e($value)
	{
		return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
	}

	function validateEmail($email) 
	{
		return filter_var($email, FILTER_VALIDATE_EMAIL);
    }


?>