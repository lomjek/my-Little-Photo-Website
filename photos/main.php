<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

if (isset($_GET['c'])) {
    $current = $_GET['c'];
} else {
    header("Location: ../");
    exit;
}


$lines = file($current . '/data.txt');

$date = $lines[0];
$name = $lines[1];
$folder = str_replace(" ", "-", $name);
$color = $lines[2];
$tcolor = $lines[3];

echo '<title>' . $name . '</title>';
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<meta charset="utf-8">';
echo '<body style="background-color:' . $color .';">';
echo '<h1 style="text-align: center; font-size: clamp(1rem, 20vw, 5rem); background-color:' . $color .'; color:' . $tcolor .';">' . $name . '</h1>';
echo '<a href="../../index.html" style="background-color:' . $color .'; font-size: 25px; color:' . $tcolor .';">Return to home</a>';

$jpgFiles = glob($current . "/*.jpg");
$JPGFiles = glob($current . "/*.JPG");
$jpegFiles = glob($current . "/*.jpeg");
$JPEGFiles = glob($current . "/*.JPEG");
$pngFiles = glob($current . "/*.png");
$PNGFiles = glob($current . "/*.PNG");
$files = array_merge($jpgFiles, $jpegFiles, $pngFiles, $JPGFiles, $JPEGFiles, $PNGFiles);

echo "<div id='mn' style='width: 100%;'>";

foreach ($files as $path) {
    $basename = pathinfo($path, PATHINFO_FILENAME);
    $ending = pathinfo($path, PATHINFO_EXTENSION);
    echo '<a href="/photo/' . $path . '"><img style="padding: 2%; float: inline-start;" width="46%" src="/thumbnails/' . $folder . '/' . $basename . '.webp" alt="' . $basename . '.webp  Loading error...  No thumbnail available. Click here to view the image!"></a>';
}
echo "</div>";

echo '<a href="../../index.html" style="background-color:' . $color .'; font-size: 25px; color:' . $tcolor .';">Return to home</a>';

?>
