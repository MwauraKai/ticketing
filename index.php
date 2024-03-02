<!DOCTYPE html>
<html>
<head>
	<title>LOGIN</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<h1>WELCOME TO OUR TICKET RESERVATION SYSTEM. <br> PLEASE LOGIN TO RESERVE A TICKET.</h1>
     <form action="login.php" method="post">
     	<h2>LOGIN</h2>
     	<?php if (isset($_GET['error'])) { ?>
     		<p class="error"><?php echo $_GET['error']; ?></p>
     	<?php } ?>
     	<label>User Name</label>
     	<input type="text" name="uname" placeholder="User Name/Email"><br>

     	<label>Password</label>
     	<input type="password" name="password" placeholder="Password"><br>

     	<p>
		   <button type="submit">Login</button>
          <a href="signup.php" class="ca">Create an account</a>
		</p>
     </form>
</body>
</html>