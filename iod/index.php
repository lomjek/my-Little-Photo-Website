<?php 
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

$currentDate = date("Ymd");
$directories = glob($_SERVER['DOCUMENT_ROOT'] . '/photos/*/', GLOB_ONLYDIR);
mt_srand($currentDate);
$randomNumber = mt_rand(0, count($directories) - 1);

$folder = $directories[$randomNumber];
echo $folder;

$randomFileIndex = mt_rand(0, count($directories) - 1);

$files = glob($folder . '/*.{jpg,png,jpeg,webp,JPG,PNG,JPEG,WEBP}', GLOB_BRACE);

$file = $files[$randomFileIndex];
$localPath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $file);
$photo_path = str_replace("/photos/", '/photo/', $localPath);
echo $photo_path;
header('Location: ' . $photo_path);
exit;
?>
