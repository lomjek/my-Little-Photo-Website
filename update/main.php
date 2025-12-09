<?php

/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

require $_SERVER['DOCUMENT_ROOT'] . '/data/server.php';
require $_SERVER['DOCUMENT_ROOT'] . '/libs/collections.php';

get_public_collections();

function display_public_collection(string $collection): void
{
    $colors = get_collection_colors($collection);
    echo '<table class="collections_content" style="background-color: ' . $colors[0] . ';" id="' . $collection . '">';
    echo '<td onclick="window.location=\'/update/collection/edit/' . $collection . '\';"><h2 style="text-align: left; margin-left: 2%; color:' . $colors[1] . '; float: left;">' . str_replace("-", " ", $collection) . '</h2></td>';
    echo '<td onclick="move(true, \'' . $collection . '\')"><h3 style="text-align: center; color: ' . $colors[1] . ';">↑</h3></td>';
    echo '<td onclick="move(false, \'' . $collection . '\')"><h3 style="text-align: center; color: ' . $colors[1] . ';">↓</h3></td>';
    echo '</table>';
}

function display_unlisted_collection(string $collection): void
{
    $colors = get_collection_colors($collection);
    echo '<table class="collections_content" style="background-color: ' . $colors[0] . ';" id="' . $collection . '">';
    echo '<td onclick="window.location=\'/update/collection/edit/' . $collection . '\';"><h2 style="text-align: left; margin-left: 2%; color:' . $colors[1] . '; float: left;">' . str_replace("-", " ", $collection) . '</h2></td>';
    echo '</table>';
}
?>
<!DOCTYPE html>
<html lang="hr">

<head>
    <title>Ažuriranje LLS</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />

    <link rel="stylesheet" href="/data/style.css">
    <link rel="stylesheet" href="main.css">

    <script src="main.js"></script>

</head>

<body>
    <h3 onclick="window.location.assign('/')">
        <= Glavna strana</h3>
            <h1 id="Title">Ažuriranje LLS</h1>

            <h3 id='open_update' onclick="window.location.assign('/update/collection/new')">Napravite novi Skup =></h3>
            <h3 id='open_update' onclick="window.location.assign('/update/images')">Dodajte Slike =></h3>

            <hr>
            <div id="Images">
                <?php
                foreach (get_collections_from_csv() as $collection) {
                    display_public_collection($collection);
                }
                ?>
            </div>

            <hr>

            <h3>Unlisted collections</h3>
            <div>
                <?php
                foreach (get_unlisted_collections() as $collection) {
                    display_unlisted_collection($collection);
                }
                ?>
            </div>

            <hr>

            <h3>Private collections</h3>
            <div>
                <?php
                foreach (get_unlisted_collections(true) as $collection) {
                    display_unlisted_collection($collection);
                }
                ?>
            </div>
</body>

</html>