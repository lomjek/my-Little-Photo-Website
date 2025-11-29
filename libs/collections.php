<?php

/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

function delete_collection($collection)
{
	$path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection;
	$files = scandir($path);
	foreach ($files as $file) {
		if ($file !== '.' && $file !== '..') {
			unlink($path . '/' . $file);
		}
	}
	rmdir($path);
}

function get_collection_colors($collection)
{
	$path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection . '/.c_';
	if (file_exists($path)) {
		$colors = explode(PHP_EOL, file_get_contents($path));
		return $colors;
	} else {
		return ['#aaa', '#333'];
	}
}
function set_collection_colors($collection, $bg_color, $text_color)
{
	$path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection . '/.c_';
	$content = $bg_color . PHP_EOL . $text_color;
	file_put_contents($path, $content);
}

function get_collection_description($collection)
{
	$path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection . '/.d_';
	if (file_exists($path)) {
		return file_get_contents($path);
	} else {
		return "";
	}
}
function set_collection_description($collection, $description)
{
	$path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection . '/.d_';
	if (!file_exists($path)) {
		touch($path);
	}
	file_put_contents($path, $description);
}

function get_collection_visibility($collection)
{
	$path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection;
	if (file_exists($path . '/.u_')) {
		return 'unlisted';
	} elseif (file_exists($path . '/.htaccess')) {
		return 'private';
	} else {
		return 'public';
	}
}
function set_collection_visibility($collection, $visibility)
{
	$path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection;
	if ($visibility == 'public') {
		if (file_exists($path . '/.u_')) {
			unlink($path . '/.u_');
		}
		if (file_exists($path . '/.htaccess')) {
			unlink($path . '/.htaccess');
		}
	} elseif ($visibility == 'unlisted') {
		if (file_exists($path . '/.htaccess')) {
			unlink($path . '/.htaccess');
		}
		file_put_contents($path . '/.u_', 'unlisted');
	} elseif ($visibility == 'private') {
		file_put_contents($path . '/.u_', 'unlisted');
		file_put_contents($path . '/.htaccess', 'AuthType Basic' . PHP_EOL . 'AuthName "Only for Admin"' . PHP_EOL . 'AuthUserFile /var/www/html/update/.htpasswd' . PHP_EOL . 'Require valid-user');
	} else {
		http_response_code(422);
		exit();
	}
}

function create_collection($name)
{
	$path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $name;
	if (mkdir($path)) {
		file_put_contents($path . '/.c_', "#aaaaaa\n#333333");
		set_collection_visibility($name, 'private');
		return true;
	} else {
		return false;
	}
}

function rename_collection($old_name, $new_name)
{
	$old_path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $old_name;
	$new_path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $new_name;
	if (rename($old_path, $new_path)) {
		return true;
	} else {
		return false;
	}
}

function collection_exists($name)
{
	$path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $name;
	return file_exists($path) && is_dir($path);
}

function get_collections($all = false)
{
	$folders = scandir($_SERVER['DOCUMENT_ROOT'] . '/photos'); //Get all items from the photos folder.
	$folders = array_diff($folders, ['.', '..']); //Remove . and ..

	foreach ($folders as $item) {
		if (!is_dir($_SERVER['DOCUMENT_ROOT'] . '/photos/' . $item)) {
			$folders = array_diff($folders, [$item]); //For each element, if it is not a dir, remove it from the folders array.
		} elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/photos/' . $item . '/.u_') && !$all) {
			$folders = array_diff($folders, [$item]);
		}
	}
	$folders = array_values($folders); //Clean up the Indexes of the Array.

	$order_file = $_SERVER["DOCUMENT_ROOT"] . "/photos/order.csv";
	if (file_exists($order_file)) { //If there is an order for the file
		$order = explode(", ", file_get_contents($order_file)); //Read the data
		foreach ($folders as $folder) { #Verify that all folders in /photos/ are in the order
			if (!in_array($folder, $order)) {
				$order[] = $folder;
			}
		}
		foreach ($order as $item) { //Verify that all elements in the order are folders in /photos/
			if (!in_array($item, $folders)) {
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

function get_image_count($path)
{
	$images = scandir($path); //Get all items from the path
	$count = 0;
	foreach ($images as $image) {
		if (str_ends_with(strtolower($image), ".webp")) {
			if (str_starts_with(strtolower($image), ".t_")) {
				$count += 1;
			}
		}
	}
	return $count;
}

function display_update_collections($order)
{
	foreach ($order as $collection) {
		$path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection;
		$colors = explode(PHP_EOL, file_get_contents($path . '/.c_'));
		echo '<table class="collections_content" style="background-color: ' . $colors[0] . ';" id="' . $collection . '">';
		echo '<td onclick="window.location=\'/update/collection/edit/' . $collection . '\';"><h2 style="text-align: left; margin-left: 2%; color:' . $colors[1] . '; float: left;">' . str_replace("-", " ", $collection) . '</h2></td>';
		echo '<td onclick="move(true, \'' . $collection . '\')"><h3 style="text-align: center; color: ' . $colors[1] . ';">↑</h3></td>';
		echo '<td onclick="move(false, \'' . $collection . '\')"><h3 style="text-align: center; color: ' . $colors[1] . ';">↓</h3></td>';
		echo '</table>';
	}
}

function display_collections($order)
{
	foreach ($order as $collection) {
		$path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection;
		$colors = explode(PHP_EOL, file_get_contents($path . '/.c_'));
		echo '<div onclick="window.location=\'/photos/' . $collection . '\';" class="collections_content" style="background-color: ' . $colors[0] . ';" id="' . $collection . '">';
		echo '<h2 class="collections_title" style="color:' . $colors[1] . ';">' . str_replace("-", " ", $collection) . '</h2>';
		echo '<h3 class="collections_img_count" style="color: ' . $colors[1] . ';">' . get_image_count($path) . ' Slika</h3>';
		echo '</div>';
	}
}

// FUNCTION CALLS FOR ACCESS VIA AJAX/POST
if (!(realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) || !($_SERVER['REQUEST_METHOD'] === 'POST')) {
	return;
}

if ($_POST['func'] == 'display_collections') {
	$order = get_collections();
	display_collections($order);
} elseif ($_POST['func'] == 'get_collections') {
	$order = get_collections();
	echo json_encode($order);
} else if ($_POST['func'] == 'rename_collection') {
	if (isset($_POST['old_name']) && isset($_POST['new_name'])) {
		if (rename_collection($_POST['old_name'], $_POST['new_name'])) {
			http_response_code(200);
		} else {
			http_response_code(500);
		}
	} else {
		http_response_code(422);
	}
} else if ($_POST['func'] == 'delete_collection') {
	if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/photos/' . $_POST['collection'])) {
		delete_collection($_POST['collection']);
		http_response_code(200);
		echo 'Collection deleted successfully: ' . htmlspecialchars($_POST['collection']);
	} else {
		http_response_code(404);
		echo 'Not Found: Collection does not exist: ' . htmlspecialchars($_POST['collection']);
	}
} elseif ($_POST['func'] == 'set_collection_description') {
	if (isset($_POST['collection']) && isset($_POST['description'])) {
		set_collection_description($_POST['collection'], $_POST['description']);
		http_response_code(200);
	} else {
		http_response_code(422);
	}
} elseif ($_POST['func'] == 'set_collection_colors') {
	if (isset($_POST['collection']) && isset($_POST['bg_color']) && isset($_POST['text_color'])) {
		set_collection_colors($_POST['collection'], $_POST['bg_color'], $_POST['text_color']);
		http_response_code(200);
	} else {
		http_response_code(422);
	}
} elseif ($_POST['func'] == 'set_collection_visibility') {
	if (isset($_POST['collection']) && isset($_POST['visibility'])) {
		set_collection_visibility($_POST['collection'], $_POST['visibility']);
		print_r($_POST);
		http_response_code(200);
	} else {
		http_response_code(422);
	}
} elseif ($_POST['func'] == 'collection_exists') {
	if (isset($_POST['collection'])) {
		if (collection_exists($_POST['collection'])) {
			return 'true';
			http_response_code(200);
		} else {
			return 'false';
			http_response_code(200);
		}
	} else {
		http_response_code(422);
	}
} else {
	http_response_code(400);
	echo 'Bad Request: Unknown function: ' . htmlspecialchars($_POST['func']);
}
