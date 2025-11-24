<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

//Interactions
function kill($response, $explanation=''){
	http_response_code($response);
	header('Content-Type: text/plain');
	print_r($explanation);
	exit;
}

//File manipulation
function rmd($dir) {
	if (!is_dir($dir)) {
		return false;
	}
	$items = scandir($dir);
	foreach ($items as $item) {
		if ($item == '.' || $item == '..') {
			continue;
		}
		$path = $dir . DIRECTORY_SEPARATOR . $item;
		if (is_dir($path)) {
			rmd($path);
		} else {
			unlink($path);
		}
	}
	return rmdir($dir);
}
function mkd($dir, $permissions = 0755) {
	if (is_dir($dir)) {
		rmd($dir);
	}
	return mkdir($dir, $permissions);
}

function get_about_us(){
	$speech = "Ako vidite ovaj tekst, onda se mojem Computeru više ne da radit, kako treba."; //This is a placeholder text and should be overwritten in the funtion.
	$a_file = $_SERVER['DOCUMENT_ROOT'] . "/data/.d_"; //Set the path to the description file.
	if (file_exists($a_file)) { //If the file exists
		$speech = file_get_contents($a_file); //Load the About us text from it.
	}
	return $speech;
}
function is_maintenance(){
	if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/data/.u_")){
		return true;
	} else {
		return false;
	}
}
?>
