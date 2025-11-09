<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

$dir = 'photos';
$output = "";

foreach (new DirectoryIterator($dir) as $file) {
    if ($file->isDir() && !$file->isDot()) { // Exclude "." and ".." directories
        
        $folderName = $file->getFilename();
        $folderPath = $dir . '/' . $folderName;
        $imgcnt = 0;

        foreach (new DirectoryIterator($folderPath) as $subfile) {
            if ($subfile->isFile()) {
                $extension = strtolower(pathinfo($subfile, PATHINFO_EXTENSION));                    
                if ($extension === 'jpg' || $extension === 'jpeg' || $extension === 'png') {
                    $imgcnt++;
                };
                
                if ($subfile->getFilename() == "data.txt") {
                    $lines = file($folderPath . '/' . $subfile);
                    $date = substr($lines[0], 0, -1);
                    $color = substr($lines[2], 0, -1);
                    $tcolor = substr($lines[3], 0, -1);

                };                
            }
        }
        echo $folderName . ":" . $imgcnt . ":" . $date . ":" . $color . ":" . $tcolor . "\n";
    };
}
?>
