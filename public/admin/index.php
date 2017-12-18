<?php 
require_once("../../includes/functions.php");
require_once("../../includes/session.php");
// require_once("../includes/users.php");
if (!$session->is_logged_in()) {
	redirect('login.php');
}
 ?>
<?php include '../layout/admin_header.php'; ?>
 		<h2>Menu</h2>
 		<?php echo output_message($message);?>

 		<div>
 			<ul>
 				<li><a href="list_photo.php" style="color:white;">List photos</a></li>
 				<li><a href="list_photo.php" style="color:white;">Logout</a> </li>

 			</ul>
 		</div>
 	</div>
 	
<?php include '../layout/admin_footer.php'; ?>