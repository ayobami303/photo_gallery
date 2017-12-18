<?php 
require_once("../../includes/initializer.php");

if (!$session->is_logged_in()) {
	redirect('login.php');
}

$photo = new Photographs();
$result = $photo->find_all();
$comment = new comments();

?>
<?php include '../layout/admin_header.php'; ?>
 		<h2>Photos</h2>
 		<?php echo output_message($message);?>
 		<div> 
 		<br>
 		<br>
 			<table class="table">
 				<th>S/N</th>
 				<th>Image</th>
 				<th>File name</th>
 				<th>Size</th>
 				<th>File type</th>
 				<th>Caption</th>
 				<th>No of comment</th>
 				<th>Action</th>
 				<tbody>
 					<?php 
 						$count = 1;
 						foreach ($result as $value) {
 							 echo  
 							 "<tr>
	 								<td>".$count."</td>
	 								<td><img  src=\"../upload/".$value->filename."\" style = \"max-height:50px; \"></td>
	 								<td>".$value->filename."</td>

	 								<td>".$photo->size_to_text($value->size)."</td>
	 								<td>".$value->type."</td>
	 								<td>".$value->caption."</td>
	 								<td><a href=\"photo.php?id=".$value->id."\">".count($comment->find_comments_on($value->id))."</a></td>
	 								<td>
	 									<a href=\" delete_photo.php?id=".$value->id."&f=".$value->filename."\" >
		 									<span class=\"label label-danger\">
												<span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span>  
												Delete
											</span>
										</a>
									</td>
 							 </tr>";
 							 $count++;
 						}
 					 ?>
 					
 				</tbody>
 			</table>
 			<br>
 			<br>

 			<a href="photo_upload.php" >Upload new photographs</a>
 		</div>
 	</div>
 	
<?php include '../layout/admin_footer.php'; ?>