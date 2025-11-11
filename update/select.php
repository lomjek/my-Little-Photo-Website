<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

$collection = $_POST['collect'];
$bgcol = $_POST['backgrnd'];
$clr = $_POST['foregrnd'];

echo "<body style='background-color: " . $bgcol . "'>";
echo "<script src='select_del.js'></script>";


$current = "../photos/" . $collection;

$jpgFiles = glob($current . "/*.jpg");
$JPGFiles = glob($current . "/*.JPG");
$jpegFiles = glob($current . "/*.jpeg");
$JPEGFiles = glob($current . "/*.JPEG");
$pngFiles = glob($current . "/*.png");
$PNGFiles = glob($current . "/*.PNG");
$files = array_merge($jpgFiles, $jpegFiles, $pngFiles, $JPGFiles, $JPEGFiles, $PNGFiles);

echo "<a href='delete_collection.php?c=" . $collection . "'><h2 style='color: " . $clr . " !important;' onclick='del_col()'>" . $collection . "</h2></a>";

echo "<div style='padding-left: 5%;'>";

foreach ($files as $file){
    $path = explode("/", $file);
    $filename = $path[count($path) - 1];
    echo "<a href='delete_file.php?c=" . $collection . "&f=" . $filename . "'><p style='color: " . $clr . " !important;'>" . $filename . "</p>";
}

echo "</div></body>";
?>