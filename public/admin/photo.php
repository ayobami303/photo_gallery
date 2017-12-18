<?php 
require_once("../../includes/initializer.php");

if (!$session->is_logged_in()) {
	redirect('login.php');
}
$comment = new comments();
$photos = new photographs();
$photo = $photos->find_by_id($_GET['id']);
if (empty($_GET['id'])) {
 	$session->message("No id was provided");
 	redirect('index.php');
 }

if (!$photo) {
 	$session->message("The photo could not be located");
 	redirect('index.php');
 }

$comments = $comment->find_comments_on($photo->id);
	// foreach ($comments as $value) {
	// 	echo "<div class=\"row\">
	//  			<div class=\"col-md-6\">
	//  				<p>".$value->author." wrote:</p>
	//  				<p>".$value->body." </p>
	//  				<p>".date_to_text($value->created)." </p>

	//  			</div>
	//  		</div>";
	// }
?> 
<?php include '../layout/admin_header.php'; ?>
	<h2>Comments For: <?php echo $photo->filename; ?></h2>
 	<?php echo output_message($message);?>

 		<img  src="../upload/<?php echo $photo->filename; ?>" style = "max-height:50px; ">
		<table class="table">
			<th>S/N</th>
			<th>Author</th>
			<th>Comment </th>
			<th>Date Created</th>
			<th>Action</th>

			<tbody>
				<?php 
					$count = 1;
					foreach ($comments as $value) {
						 echo  
						 "<tr>
								<td>".$count."</td>
								
								<td>".$value->author."</td>

								<td>".$value->body."</td>
								<td>".date_to_text($value->created)."</td>
								<td>
									<a href=\" delete_comment.php?id=".$value->id."\" >
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
 	</div>
 <?php include '../layout/admin_footer.php'; ?>