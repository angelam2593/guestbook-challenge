<?php include('server.php'); ?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Guestbook</title>
    <style>
      .centered {
        margin-left:auto;
        margin-right:auto;
        width:80%;
        }
      </style>
  </head>
  <body>
    <div class="row">
      <div class="col-md-12">
        <h3>Home page</h3>
        <?php if (isset($_SESSION['success'])): ?>
          <?php 
            echo e($_SESSION['success']); 
            unset($_SESSION['success']);
          ?>
        <?php endif ?>

        <?php if (isset($_SESSION['username'])): ?>
          <?php 
            echo e($_SESSION['username']); 
          ?>
        <?php endif ?>
        <a href="index.php?logout='1'">Logout</a>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
         <form action="index.php" method="post">
           <input type="hidden" name="token" value="<?php echo e(Token::generate()); ?>">
           <textarea name="message" rows="10" cols=59></textarea><br>
           <input type="submit" name="insertMessage" value="Enter">
           <input type="reset" name="reset" value="Clear Text">
          </form>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <h3>Forum <small>[all messages]</small></h3>
        <?php 
            if (mysqli_num_rows($result_message) > 0) 
            {
                while($row = mysqli_fetch_assoc($result_message)) 
                {
                    echo "<b>Message</b>: ".e($row["body"]). " [".e($row["updated_at"])."] by ".e(get_user_by($db, $row['user_id']))."<br>";
                    echo e(getAllComments($db, $row["id"])); 
                    echo "<form action='addComment.php' method='POST'>
                            <input name='userId' type='hidden' value=".e($_SESSION["user_id"]).">
                            <input name='messageId' type='hidden' value=".e($row["id"]).">
                            <button>Add comment</button>
                          </form>";
                    if($row["user_id"] == $_SESSION["user_id"])
                      {
                        echo "<form action='editMessage.php' method='POST'>
                                <input name='messageId' type='hidden' value=".e($row["id"]).">
                                <button>Edit this message</button>
                            </form>";
                        echo "<form action='deleteMessage.php' method='POST'>
                                <input name='messageId' type='hidden' value=".e($row["id"]).">
                                <button>Delete this message</button>
                            </form>";
                      }
                }
            } 
            else 
            {
                echo "0 messages";
            }
        ?>
      </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>