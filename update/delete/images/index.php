<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

function unlink_with_extension($file, $extensions = ['jpg', 'png', 'webp', 'jpeg']) {
    if (file_exists($file)) {
        unlink($file);
        return true;
    }

    // Try each extension if no exact match is found
    foreach ($extensions as $ext) {
        $file_with_ext = $file . '.' . $ext;
        if (file_exists($file_with_ext)) {
            unlink($file_with_ext);
            return true;
        }
    }

    return false;
}

$filePath = $_SERVER['DOCUMENT_ROOT'] . "/photos/" . html_specialchars($_GET['c']) . "/" . $_GET['f'];
echo $filePath;
if (unlink_with_extension($filePath)) {
    echo "File deleted successfully!";
} else {
    echo "Error deleting file.";
}
?>
<!DOCTYPE html>
<html>
    <br>
    <a href="../../" style="color: #333; font-size: 40px; text-align: center;">Back to home!</a>
</html>
