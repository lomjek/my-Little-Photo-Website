<?php 
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

$currentDate = date("Ymd");
print_r($currentDate);
$directories = glob($_SERVER['DOCUMENT_ROOT'] . '/photos/*/', GLOB_ONLYDIR);
print_r($directories);
mt_srand($currentDate);
$randomNumber = mt_rand(1, count($directories));
print_r($randomNumber);

$folder = $directories[$randomNumber];
print_r($folder);

$files = glob($folder . '/*.{jpg,png,jpeg,webp,JPG,PNG,JPEG,WEBP}', GLOB_BRACE);

$randomFileIndex = mt_rand(1, count($files));

$file = $files[$randomFileIndex];
$localPath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $file);
$photo_path = str_replace("/photos/", '/photo/', $localPath);
echo $photo_path;
header('Location: ' . $photo_path);
exit;
?>
