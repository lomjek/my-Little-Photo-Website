<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

$cname = $_POST['cname'];

$folderPath = $_SERVER['DOCUMENT_ROOT'] . "/thumbnails/" . $cname . "/";
$files = glob($folderPath . '/*'); // Get all files and folders in the directory

// Filter out .txt files
$filteredFiles = array_filter($files, function($file) {
    return pathinfo($file, PATHINFO_EXTENSION) !== 'txt';
});

// Get only filenames (remove path part)
$filenames = array_map(function($file) {
    return pathinfo($file, PATHINFO_FILENAME); // Get filename without extension
}, $filteredFiles);
echo implode(":", $filenames);
?>