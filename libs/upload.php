<?php

/*******************************************************/
/* This file is part of 'my Little Photo Website'    */
/* It is published on github under the LGPLv3 License: */
/* https://github.com/lomjek/my-Little-Photo-Website  */
/*******************************************************/

require_once $_SERVER['DOCUMENT_ROOT'] . "/data/server.php";

function process_image($input_path, $collection, $file_name) : void
{
	$exe_path = $_SERVER['DOCUMENT_ROOT'] . "/libs/process_image.dc";
	$redirect_output = " > /dev/null 2>&1 &";
	$command = "nohup " . $exe_path ." ". escapeshellarg($input_path) . " " . escapeshellarg($collection) . " " . escapeshellarg($file_name) . $redirect_output;
	exec($command);
	echo $command;
}

function upload_success() {
	if (!isset($_FILES['files']['error'])) {
		return false;
	}

	$errors = $_FILES['files']['error'];

	if (is_array($errors)) {
		foreach ($errors as $error) {
			if ($error !== 0) {
				return false;
			}
		}
	} else {
		if ($errors !== 0) {
			return false;
		}
	}

	return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (upload_success() && isset($_POST['collection'])){
		echo "Received all files, starting processing";
		http_response_code(200);
	}
	else {
		echo "Something couldn't upload...";
		http_response_code(400);
		exit(0);
	}

	$file_array = $_FILES['files'];
	$file_count = count($file_array['name']);
	$collection = $_POST['collection'];

	$internal_tmp_dir = sys_get_temp_dir() . "/img_proc_" . uniqid();
	if (!is_dir($internal_tmp_dir)) {
		mkdir($internal_tmp_dir, 0700, true);
	}

	for ($i = 0; $i < $file_count; $i++) {
		$file_tmp_path = $file_array['tmp_name'][$i];
		$original_name = str_replace(" ", "-", $file_array['name'][$i]);
		
		$persistent_tmp_path = $internal_tmp_dir . "/" . $original_name;
		
		if (move_uploaded_file($file_tmp_path, $persistent_tmp_path)) {
			$target_name = $upload_dir . $original_name;
			process_image($persistent_tmp_path, $collection, $target_name);
		}
	}
} else {
	http_response_code(405);
	header('Allow: POST');
	echo 'Only POST requests are allowed.';
}