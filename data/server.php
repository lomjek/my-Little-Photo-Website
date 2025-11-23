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
function get_collections(){
	$folders = scandir($_SERVER['DOCUMENT_ROOT'] . '/photos'); //Get all items from the photos folder.
	$folders = array_diff($folders, ['.', '..']); //Remove . and ..
	foreach ($folders as $item) {
		if (!is_dir($_SERVER['DOCUMENT_ROOT'] . '/photos/' . $item)) { 
			$folders = array_diff($folders, [$item]); //For each element, if it is not a dir, remove it from the folders array.
		} elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/photos/' . $item . '/.u_')){
			$folders = array_diff($folders, [$item]);
		}
	}
	$folders = array_values($folders); //Clean up the Indexes of the Array.

	$order_file = $_SERVER["DOCUMENT_ROOT"] . "/photos/order.csv";
	if (file_exists($order_file)){ //If there is an order for the file
		$order = explode(", ", file_get_contents($order_file)); //Read the data
		foreach ($folders as $folder){ #Verify that all folders in /photos/ are in the order
			if (!in_array($folder, $order)){
				$order[] = $folder;
			}
		}
		foreach ($order as $item){ //Verify that all elements in the order are folders in /photos/
			if (!in_array($item, $folders)){
				$order = array_diff($order, [$item]);
			}
		}
		$order = array_values($order); //Repair the indexes
		file_put_contents($order_file, implode(", ", $order)); //Save the verified list.
	} else {
		$order = $folders; //If there is no order file, just store the folders list into the file
		file_put_contents($order_file, implode(", ", $order));
	}
	return $order; //return order
}
function is_maintenance(){
	if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/data/.u_")){
		return true;
	} else {
		return false;
	}
}
function get_image_count($path){
	$images = scandir($path); //Get all items from the path
	$count = 0;
	foreach ($images as $image) {
		if (str_ends_with(strtolower($image), ".webp")){
			if (str_starts_with(strtolower($image), ".t_")){
				$count += 1;
			}
		}
	}
	return $count;
}
function display_collections($order, $update = false){
	foreach ($order as $collection){
		$path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection;
		$colors = explode(PHP_EOL, file_get_contents($path . '/.c_'));
		echo '<table  style="background-color: ' . $colors[0] . ';" id="' . $collection . '">';
		if ($update){
			echo '<td onclick="window.location=\'/update/collection/edit/' . $collection . '\';"><h2 style="text-align: left; margin-left: 2%; color:' . $colors[1] . '; float: left;">' . str_replace("-", " ", $collection) . '</h2></td>';
			echo '<td onclick="move(true, \'' . $collection . '\')"><h3 style="text-align: center; color: ' . $colors[1] . ';">↑</h3></td>';
			echo '<td onclick="move(false, \'' . $collection . '\')"><h3 style="text-align: center; color: ' . $colors[1] . ';">↓</h3></td>';
		} else {
			echo '<td onclick="window.location=\'/photos/' . $collection . '\';"><h2 style="text-align: left; margin-left: 2%; color:' . $colors[1] . '; float: left;">' . str_replace("-", " ", $collection) . '</h2></td>';
			echo '<td><h3 style="text-align: right; color: ' . $colors[1] . '; float: right;">' . get_image_count($path) . ' Slika</h3></td>';
		}
		echo '</table>';
	}
}
?>
