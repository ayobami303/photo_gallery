<?php 
// require_once("../includes/functions.php");
// require_once("../includes/database.php");
// require_once("../includes/users.php");
require_once("../includes/initializer.php");

 ?>

 <?php include 'layout/header.php'; ?>
		
		<?php 
			 $photo = new photographs();
			 //$record = $photo->find_all();
			
			$per_page = 3;
			$current_page = (!empty($_GET['page'])) ? (int)$_GET['page'] : 1 ;
			$total_count = $photo->count_all();
			$pag = new pagination($current_page,$per_page,$total_count);

			$sql = "SELECT * FROM photographs";
			$sql .= " LIMIT ".$per_page;
			$sql .= " OFFSET ".$pag->offset();

			$record = $photo->find_by_sql($sql);
		?>

		<div>
			<?php echo output_message($message);?>
			<?php 
				foreach ($record as $value) {
					echo "<div style=\"float:left; width:200px;\">
							<div style = \"height:200px; \">
								<a href =\"view_photo.php?id=".$value->id."&f=".$value->filename." \">
									<img  src=\"upload/".$value->filename."\" style = \"max-height:200px; max-width:195px; \">
								</a>	
							</div>	
	 					<p>".$value->caption."</p>
	 				</div>";
				}
			?>

 			<div class="row " style="clear:both;">
 				<div class ="col-md-6">
	 				<?php 
	 					if ($pag->total_page() > 1 ) {
	 						if ($pag->has_previous()) {
	 							echo "<p><a href=\"index.php?page=".$pag->previous_page()."\"> << Previous </a>";
	 						}
	 						for($i = 1; $i <= $pag->total_page(); $i++) {
	 							
	 							if ($i == $current_page) {
	 								echo "<span style=\"font-weight:bold; \">".$i."</span> ";
	 							}else{
	 								echo "<a href=\"index.php?page=".$i."\" >".$i."</a> ";
	 							}
	 						}
	 						if ($pag->has_next()) {
	 							echo "<a href=\"index.php?page=".$pag->next_page()."\"> next >> </a></p>";		
	 						}
	 					}
	 					
	 				?>
 				</div>
 			</div>	
 			
 		</div>
 	</div>
 <?php include 'layout/footer.php'; ?>	