<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

$collection = $_GET['s'];
$file = $_GET['f'];

$collection = urldecode($collection);
$file = urldecode($file);

$path = '/photos/' . $collection . "/" . $file;

echo "<link rel='stylesheet' href='/photo/viewer.css' type='text/css'/>";

echo "<body>";

echo "<div class='container'>";

echo "<img id='img' src='" . $path . "' alt='Something strange...'>";

echo "</div>";

echo "<a href='/photos/" . $collection . "/'><img id='back' src='/photo/arrow.png'/></a>";

echo "</body>";
?>
