<?php

/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

require_once $_SERVER['DOCUMENT_ROOT'] . '/libs/collections.php';
$order = get_collections();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dodaj Slike</title>

    <link rel="stylesheet" href="/update/images/index.css">
    <link rel="stylesheet" href="/data/style.css">

    <script src="/update/images/index.js"></script>
    <style>
        button {
            width: 100%;
            height: clamp(0.7rem, 9vw, 5rem);
            font-size: clamp(0.5rem, 7vw, 4rem);
        }
    </style>
</head>

<body>
    <h3 id="leave" onclick="window.location = '/update/'">
        <= Ažuriranje LLS</h3>
            <h1 id='Title' style="color: #333333;">Dodajte Slike</h1>

            <div style="margin-bottom: 20px; clear: both;"></div>

            <select id="skupovi" style="width: 50%; font-size: clamp(0.5rem, 7vw, 4rem); float: left;">
                <?php
                foreach ($order as $collection) {
                    echo "<option value='" . $collection . "'>" . $collection . "</option>";
                }
                ?>
            </select>
            <input type="file" id="files" name="files[]" multiple accept=".jpg, .jpeg, .png, .webp, .tif, .tiff, .j2c, .jp2, .bmp" style="width: 50%; font-size: clamp(0.5rem, 7vw, 4rem); float: right;">
            <div style="margin-bottom: 20px; clear: both;"></div>
            <button id="Upload" onclick="Upload()">Upload</button>

            <div id='uploading' style="display: none;">
                <progress id="progressBar" style="width: 100%; height: clamp(0.7rem, 9vw, 5rem);" value="0" max="100"></progress>
            </div>

            <div style="margin-bottom: 20px; clear: both;"></div>

</body>

</html>