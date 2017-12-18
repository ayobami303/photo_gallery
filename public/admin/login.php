<?php 
// require_once("../../includes/functions.php");
// require_once("../../includes/database.php");
// require_once("../../includes/session.php");
require_once("../../includes/initializer.php");

if ($session->is_logged_in()) {
	redirect('index.php');
}
$message='';
if (isset($_POST['submit'])) {
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	$users = new Users();
	$found_user = $users->authenticate($username,$password);

	if ($found_user) {
		$session->login($found_user);
		redirect('index.php');
	} else {
		$message = "username/password combination incorrect";
	}
	
} else {
	$username = '';
	$password = '';
}

 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title>Photo Gallery</title>
 	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">

 	<style type="text/css">
 		.main{
 			min-height: 500px;
 		}
 		.header{
 			min-height: 60px;
 			background-color: #092f56;
 			color:white;
 		}
 		.header h1{
 			padding: 10px;
 			margin: 0;
 		}
 		.main{
 			background-color: #7b8b49;
 			padding: 10px;
 		}
 	</style>
 </head>
 <body class="container">
 	<div class="header">
 		<h1>Photo Gallery</h1>
 	</div>
 	<div class="main">
 		<h2>Staff Login</h2>
 		<div class="col-md-4">

 			<?php echo output_message($message); ?>
 			<form action="login.php" method="post" class="form">
 				<div class="form-group ">
	 				<input type="text" name="username" placeholder="Username" class="form-control" value="<?php echo htmlentities($username);?>">
	 			</div>
	 			<div class="form-group ">
	 				<input type="password" name="password" placeholder="Password" class="form-control" value="<?php echo htmlentities($password);?>">
	 			</div>
	 			<div class="form-group ">
	 				<input type="submit" name="submit" value="submit" class="form-control btn btn-info">
	 			</div>
	 		</form>
 		</div>
 		
 	</div>
 	<div class="footer">
 		<p>Copyright &copy <?php echo date('Y',time()); ?>
 	</div>
 </body>
 </html>
