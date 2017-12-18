<div class="footer">
		<hr/>
 		<p>Copyright &copy <?php echo date('Y',time()); ?> Omotosho</p>
 	</div>
 </body>
 </html>
 <?php if (isset($database)) {
 	$database->close_connection();
 } ?>