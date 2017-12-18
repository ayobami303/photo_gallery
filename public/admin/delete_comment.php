<?php
require_once("../../includes/initializer.php");

if (!$session->is_logged_in()) {
	redirect('login.php');
}

if (empty($_GET['id'])) {
	$session->message("No photograph id was provided");
	redirect('index.php');
} 

$comment = new comments();
$comments = $comment->find_by_id($_GET['id']);
if ($comments && $comments->delete($_GET['id'])) {
	$session->message("the comment has been deleted");
	redirect('list_photo.php');
} else {
	$session->message("the comment could not be deleted");
	redirect('list_photo.php');
}

?>