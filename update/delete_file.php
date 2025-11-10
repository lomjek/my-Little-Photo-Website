<?php 
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

$collection = $_GET['c'];
$file = $_GET['f'];
$path = "../photos/" . $collection . "/" . $file;
if (unlink($path)){
    echo "File deleted succesfully...\n";
    echo "<a href='../'>Home</a>";
} else {
    echo "There was an error\n";
    echo "<a href='./'>Back to Update</a>";
}
?>
