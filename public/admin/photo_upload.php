<?php 
require_once("../../includes/initializer.php");

if (!$session->is_logged_in()) {
	redirect('login.php');
}

$max_file_size = 1048576;
if (isset($_POST['submit'])) {
	$photo = new Photographs();
	$photo->caption = $_POST['caption'];
	$photo->attach_file($_FILES['file_upload']);

	if ($photo->save()) {
		//success
		$session->message("photo uploaded successfully.");
		redirect('list_photo.php');
	} else {
		//failed
		$message = join("</br>",$photo->errors);
	}
	
} else {
	# code...
}

$photo = new Photographs();
$result = $photo->find_all();

 ?>
<?php include '../layout/admin_header.php'; ?>
 		<h2>Photo Upload</h2>
 		<?php echo output_message($message);?>
 		<div class="col-md-4">
 			<form action="photo_upload.php" enctype="multipart/form-data" method="post">
 				<div class="form-group">
 					<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size; ?>">
 					<label>Upload Photograph</label>
 					<input type="file" name="file_upload" >
 				</div>
 				<div class="form-group">
 					<label>caption</label>
 					<input type="text" name="caption" value="" class="form-control">
 				</div>
 				<div class="form-group">
 					<input class="btn btn-default" type="submit" name="submit" value="Upload">
 				</div>
 			</form>
 		</div>

 	</div>
 	
<?php include '../layout/admin_footer.php'; ?>