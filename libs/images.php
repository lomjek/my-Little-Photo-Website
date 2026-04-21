<?php

/*******************************************************/
/*   This file is part of 'my Little Photo Website'    */
/* It is published on github under the LGPLv3 License: */
/*  https://github.com/lomjek/my-Little-Photo-Website  */
/*******************************************************/

require_once $_SERVER['DOCUMENT_ROOT'] . '/libs/collections.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/data/server.php';

#region Image Functions
function delete_image(string $collection, string $image): bool
{
	$path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection . '/' . $image;
	$thumbnail_path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection . '/.t_' . $image;
	if (!collection_exists($collection)) {
		echo "CnF: " . $collection;
		return false;
	}
	if (file_exists($path)) {
		unlink($path);
	}
	if (file_exists($thumbnail_path)) {
		unlink($thumbnail_path);
	}
	return true;
}
function image_exists($collection, $image)
{
	$path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection . '/' . $image;
	return file_exists($path);
}

function get_image_description($collection, $image)
{
	$path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection . '/.d_' . $image . '.md';
	if (file_exists($path)) {
		return file_get_contents($path);
	} else {
		return "";
	}
}

function set_image_description($collection, $image, $description)
{
	$path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection . '/.d_' . $image . '.md';
	if (!file_exists($path)) {
		touch($path);
	}
	file_put_contents($path, $description);
	return file_get_contents($path);
}
#endregion
#region Image Fetchers
function get_ar($imagePath) : int {
	try {
		$imageInfo = getimagesize($imagePath);
		if (!$imageInfo) {
			throw new Exception("Failed to retrieve image information");
		}

		$width = $imageInfo[0];
		$height = $imageInfo[1];

		if ($height == 0) {
			throw new Exception("Image height is zero");
		}

		$aspectRatio = round($width / $height, 2) * 100;

		return $aspectRatio;
	} catch (Exception $e) {
		error_log("Error retrieving image aspect ratio: " . $e->getMessage());
		return 0;
	}
}

function get_images_in_collection($collection)
{
	$path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection;
	$files = scandir($path);
	$images = [];
	foreach ($files as $file) {
		if (str_ends_with($file, '.webp') || str_ends_with($file, '.avif')) {
			if (str_starts_with($file, '.t_') || str_starts_with($file, '.d_')) {
				continue;
			}
			$images[] = $file;
		}
	}
	return $images;
}
function get_images_in_collection_for_update($collection)
{
	$images = get_images_in_collection($collection);
	$result = [];
	foreach ($images as $image) {
		$result[] = [
			'name' => $image,
			'description' => get_image_description($collection, $image),
			'aspect_ratio' => get_ar($_SERVER['DOCUMENT_ROOT'] . "/photos/" . $collection . "/.t_" . $image)
		];
	}
	return $result;
}
#endregion
#region API
if (realpath(__FILE__) != realpath($_SERVER['SCRIPT_FILENAME'])) {
	return;
}

if ($_POST['func'] == 'get_images_in_collection') {
	$collection = $_POST['collection'];
	echo json_encode(get_images_in_collection($collection));
	if ($collection == null) {
		http_response_code(400);
	} else {
		http_response_code(200);
	}
} elseif ($_POST['func'] == 'get_images_in_collection_for_update') {
	$collection = $_POST['collection'];
	echo json_encode(get_images_in_collection_for_update($collection));
	if ($collection == null) {
		http_response_code(400);
	} else {
		http_response_code(200);
	}
} elseif ($_POST['func'] == 'rename_image') {
	$collection = $_POST['collection'];
	$image = $_POST['old_name'];
	$new_image_name = $_POST['new_name'];
	if ($collection != null && $image != null && $new_image_name != null) {
		$command = $_SERVER['DOCUMENT_ROOT'] . '/libs/images.dc rename_image ' . $collection . " " . $image . " " . $new_image_name; 
		echo exec($command);
		http_response_code(200);
		exit;
	} else {
		http_response_code(400);
	}
} elseif ($_POST['func'] == 'set_image_description') {
	$collection = $_POST['collection'];
	$image = $_POST['image'];
	$description = $_POST['description'];
	if ($collection != null && $image != null) {
		echo set_image_description($collection, $image, $description);
		http_response_code(200);
	} else {
		http_response_code(400);
	}
} elseif ($_POST['func'] == 'delete_image') {
	$collection = $_POST['collection'];
	$image = $_POST['image'];
	if ($collection != null && $image != null) {
		delete_image($collection, $image);
		http_response_code(200);
	} else {
		http_response_code(400);
	}
} elseif ($_POST['func'] == 'change_collection_of_image') {
	$collection = $_POST['collection'];
	$image = $_POST['image'];
	$new_collection = $_POST['new_collection'];
	if ($collection != null && $image != null && $new_collection != null) {
		echo exec($_SERVER['DOCUMENT_ROOT'] . '/libs/images.dc change_collection_of_image ' . $collection . ' ' . $image . '.webp ' . $new_collection);
		http_response_code(200);
	}
	else{
		http_response_code(400);
	}
} else {
	http_response_code(422);
	echo 'Bad Request: Unknown function: ' . htmlspecialchars($_POST['func']);
	exit;
}
#endregion
