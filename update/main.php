<?php

/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

require $_SERVER['DOCUMENT_ROOT'] . '/data/server.php';
require $_SERVER['DOCUMENT_ROOT'] . '/libs/collections.php';
get_collections();
$order = get_collections(true);
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
                <?php display_update_collections($order); ?>
            </div>
</body>

</html>