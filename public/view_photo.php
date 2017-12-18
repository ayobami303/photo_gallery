<?php 
// require_once("../includes/functions.php");
// require_once("../includes/database.php");
// require_once("../includes/users.php");
require_once("../includes/initializer.php");

 ?>

 <?php include 'layout/header.php'; ?>
		<p><a href="index.php"> << back </a></p>
		<?php 
			$Comment = new comments();
			$photos = new photographs();
			/* $record = $User->find_by_id(1);
			 $User = new Users();
			 $record = $User->find_by_id(1);*/

			 // echo $record['username'];
			 //echo $record->full_name();
			 /*$users = $User->find_all();
			 foreach ($users as $value) {
			 	echo 'Username:'.$value->username;
			 	
			 	echo ' </br> Name:'.$record->full_name();
			 }*/
			 if (empty($_GET['id'])) {
			 	$session->message("No id was provided");
			 	redirect('index.php');
			 }
			 $photo = $photos->find_by_id($_GET['id']);
			 if (!$photo) {
			 	$session->message("The photo could not be located");
			 	redirect('index.php');
			 }
			 
			 if (isset($_POST['submit'])) {
			 	$author = trim($_POST['author']);
			 	$body = trim($_POST['body']);
			 	
			 	$record = $Comment->make($photo->id,$author,$body);

			 	if ($record && $record->save()) {
			 		//comment saved
			 		$Comment->try_to_send_notification();
			 		redirect('view_photo.php?id='.$photo->id);
			 	}else{
			 		//saved failed
			 		$message = "There was an error that prevented comment from being saved";
			 	}
			 } else {
			 	$author = '';
			 	$body = '';
			 }

			 $comments = $Comment->find_comments_on($photo->id);
			 
		?>

		<div class="row">
			<div class="col-md-6">
				<?php 
					echo "<img  src=\"upload/".$photo->filename."\">";
				?>
			</div>
 		</div>

 			<?php 
				foreach ($comments as $value) {
					echo "<div class=\"row\">
			 			<div class=\"col-md-6\">
			 				<p>".$value->author." wrote:</p>
			 				<p>".$value->body." </p>
			 				<p>".date_to_text($value->created)." </p>
			 				<hr/>
			 			</div>
			 		</div>";
				}
 			 ?> 
 		
 		<div class="row">
	 		<div class="col-md-4">
	 			<h3>New Comments</h3>

	 			<?php echo output_message($message); ?>
	 			<form action="view_photo.php?id=<?php echo $photo->id;?>" method="POST">
	 				<div class="form-group">
	 					<input type="text" name="author" class="form-control" placeholder="Your Name" value="<?php echo $author; ?>">
	 				</div>
	 				<div class="form-group">
	 					<textarea class="form-control" cols="30" rows="7" name="body" placeholder="Comments"><?php echo $body; ?></textarea> 
	 				</div>	
	 				<input type="submit" name="submit" value="submit Comment" class="btn btn-default">
	 			</form>
	 		</div>
	 	</div>	
 	</div>
 <?php include 'layout/footer.php'; ?>	