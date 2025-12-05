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
</head>

<body>
    <h3 id="leave" onclick="window.location = '/update/'">← Ažuriranje LLS</h3>

    <h1 id='Title'>Dodajte Slike</h1>

    <hr>

    <div id="Container">

        <select id="skupovi">
            <?php
            foreach ($order as $collection) {
                echo "<option value='" . $collection . "'>" . $collection . "</option>";
            }
            ?>
        </select>

        <input id="files" name="files[]" type="file" multiple accept="image/*" />

        <button id="Upload" onclick="Upload()">Upload</button>

        <div id='uploading' style="display: none;">
            <progress id="progressBar" style="width: 100%; height: clamp(0.7rem, 9vw, 5rem);" value="0" max="100"></progress>
        </div>

    </div>

    <hr>

</body>

</html>