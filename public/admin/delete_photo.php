<?php
require_once("../../includes/initializer.php");

if (!$session->is_logged_in()) {
	redirect('login.php');
}

if (empty($_GET['id']) || empty($_GET['f'])) {
	$session->message("No photograph id was provided");
	redirect('index.php');
} 

$photo = new photographs();
$photos = $photo->find_by_id($_GET['id']);
if ($photos && $photo->destroy($_GET['id'],$_GET['f'])) {
	$session->message("the photo has been deleted");
	redirect('list_photo.php');
} else {
	$session->message("the photo could not be deleted");
	redirect('list_photo.php');
}

?>