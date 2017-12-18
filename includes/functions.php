<?php 

function redirect($Location= NULL){
	if ($Location!= NULL) {
		header('Location:'.$Location);
		exit;
	}
}

function strip_zeros_from_date($marked_string =""){
	$no_zeros = str_replace("*0", '', $marked_string);
	$cleaned_string = str_replace('*', 'replace', $no_zeros);

}
function output_message($message){
	if (!empty($message)){
		return "<p class= \"message\">{$message}</p>";
	}else{
		return "";
	}
				
}

function __autoload($class_name)
{
	$class_name = strtolower($class_name);
	$path = "../includes/{$class_name}.php";
	if(file_exists($path)){
		require_once ($path);		
	}else{
		die("the file {$class_name}.php could not be found");
	}
}

function date_to_text($datetime='')
{
	$unixtime = strtotime($datetime);
	return strftime("%B %d %Y at %I:%M %p",$unixtime);
}

 ?>
