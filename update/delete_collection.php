<?php 
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

$collection = $_GET['c'];

function del_file($path){
    if (unlink($path)){
        echo "File deleted succesfully...\n";
    } else {
        echo "There was an error with file " . $path . "\n";
    }
}

function rmsubfiles($dir){
    if (is_dir($dir)){
        $objects = scandir($dir);
        foreach ($objects as $object){
            if ($object != "." && $object != ".."){
                if (is_file($dir . "/" . $object)){
                    del_file($dir . "/" . $object);
                }
            }
        }
        reset($objects);
    }
}

if(rmsubfiles("../photos/" . $collection)){
    echo "All subfiles deleted succesfully\n";
} else {
    echo error_get_last();
    echo "There was an error...\n";
    echo "<a href='./'>Update Page</a>";
}

if (rmdir("../photos/" . $collection)){
    echo "Everything went fine. Deleted collection.\n";
    echo "<a href='../'>Home</a>";

} else {
    echo error_get_last();
    echo "There was an error removing the folder.\n";
    echo "<a href='./'>Update Page</a>";
}

?>
