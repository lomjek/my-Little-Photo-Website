<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

$content = $_POST['speech'];
$a_file = $_SERVER['DOCUMENT_ROOT'] . "/about/thing.txt";
file_put_contents($a_file, $content);
echo "<script>alert('Changes saved. Yay');</script>";
header("Location: ../");
?>
