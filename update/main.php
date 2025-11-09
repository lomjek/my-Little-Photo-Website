<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

$lines = file('data.txt');

$date = $lines[0];
$name = $lines[1];
$color = $lines[2];
$tcolor = $lines[3];

echo '<body style="background-color:' . $color .';">';
echo '<h1 style="text-align: center; font-size: 100px; background-color:' . $color .'; color:' . $tcolor .';">' . $name . '</h1>';
echo '<a href="../../index.html" style="background-color:' . $color .'; font-size: 25px; color:' . $tcolor .';">Return to home</a>';

$jpgFiles = glob("*.jpg");
$jpegFiles = glob("*.jpeg");
$pngFiles = glob("*.png");
$files = array_merge($jpgFiles, $jpegFiles, $pngFiles);


foreach ($files as $file) {
    echo '<img style="width: 100%; padding-top: 5px; padding-bottom: 5;" src="' . $file . '" alt="Loading error.">';
}
echo '<a href="../../index.html" style="background-color:' . $color .'; font-size: 25px; color:' . $tcolor .';">Return to home</a>';
?>
