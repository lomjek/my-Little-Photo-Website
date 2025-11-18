<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

$root = $_SERVER['DOCUMENT_ROOT'];
$o_file = $root . "/photos/order.csv";
$dir = $root . '/photos';

$order = [];
if (file_exists($o_file)) {
    $line = file_get_contents($o_file);
    $folderNames = explode(',', $line);

    foreach ($folderNames as $folderName) {
        $order[] = str_replace(' ', '-', trim($folderName));
    }
}

// Remove duplicates and save back to CSV file
$cleaned = implode(", ", array_unique($order));
file_put_contents($o_file, $cleaned);

foreach ($order as $folderName) {
    $folderPath = $dir . '/' . $folderName;

    if (is_dir($folderPath)) {
        $imgcnt = 0;
        $date = $color = $tcolor = '';

        foreach (new DirectoryIterator($folderPath) as $subfile) {
            if ($subfile->isFile()) {
                $extension = strtolower(pathinfo($subfile->getPathname(), PATHINFO_EXTENSION));
                if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                    $imgcnt++;
                }

                if ($subfile->getFilename() == "data.txt") {
                    $lines = file($subfile->getPathname());
                    $date = isset($lines[0]) ? trim($lines[0]) : '';
                    $color = isset($lines[1]) ? trim($lines[1]) : '';
                    $tcolor = isset($lines[2]) ? trim($lines[2]) : '';
                }
            }
        }
        echo $folderName . ":" . $imgcnt . ":" . $date . ":" . $color . ":" . $tcolor . PHP_EOL;
    }
}
?>
