<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

$collection = htmlspecialchars($_GET['s']);
$file = htmlspecialchars($_GET['f']);
$path = $_SERVER['DOCUMENT_ROOT']  . "/photos/"  . $collection . "/" . $file;
$tpath = $_SERVER['DOCUMENT_ROOT']  . "/photos/"  . $collection . "/.t_" . $file;
print($path . "<br>" .  $tpath);
unlink($path);
unlink($tpath);
print("succes")
?>
