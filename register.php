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
         <div class="card centered" style="width: 400px; margin-top: 200px;">
            <div class="card-header">
              Register
            </div>
            <div class="card-body">
              <form method="post" action="register.php">
                <input type="hidden" name="token" value="<?php echo e(Token::generate()); ?>">
                <?php include('errors.php'); ?>
                <div class="input-group">
                  <label>Username</label>
                  <input type="text" name="username" value="<?php echo e($username); ?>">
                </div>
                <div class="input-group">
                  <label>Email</label>
                  <input type="text" name="email" value="<?php echo e($email); ?>">
                </div>
                <div class="input-group">
                  <label>Password</label>
                  <input type="password" name="password_1">
                </div>
                <div class="input-group">
                  <label>Confirm Password</label>
                  <input type="password" name="password_2">
                </div>
                <div class="input-group">
                  <button type="submit" name="register" class="btn btn-primary">Register</button>
                </div>
                <p>
                  Already a member? <a href="login.php">Sign in</a>
                </p>
              </form>
            </div>
          </div>
      </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>