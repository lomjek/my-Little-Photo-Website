<?php

/*******************************************************/
/*   This file is part of 'my Little Photo Website'    */
/* It is published on github under the LGPLv3 License: */
/*  https://github.com/lomjek/my-Little-Photo-Website  */
/*******************************************************/

require $_SERVER['DOCUMENT_ROOT'] . '/data/server.php';
?>

<!DOCTYPE html>
<html lang="hr">

<head>
    <title>Lovro Leon Slike</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="description" content="<?php echo get_about_us(); ?>">

    <link rel="stylesheet" href="data/style.css">
    <link rel="stylesheet" href="index.css">

    <script src="index.js" defer></script>
</head>

<body id="main">
    <h4 class="margin_10_px" onclick="window.location.assign('update')" id="open_update">ADMIN></h4>

    <h1 id="Title">Lovro Leon Slike</h1>
    <?php if (is_maintenance()) {
        echo '<h3 id="maintenance" class="margin_10_px">Ima rade na serveru, tako da je moguče, da u sljedeče vrijeme naletite na probleme.</h3>';
    } ?>

    <hr>

    <h2 id="iod_title" class="margin_10_px">Slika dana:</h2>

    <div id="iod_container">
        <img id="iod_img" class="margin_10_px" src="<?php echo shell_exec($_SERVER['DOCUMENT_ROOT'] . '/libs/iod.dc'); ?>" alt="The Image of the Day could not be loaded.">
    </div>

    <h3 id="iod_link" class="margin_10_px">Otvori Sliku></h3>
    <h3 id="iod_description" class="margin_10_px">Ovdje ćete svaki dan vidjeti drugu sliku, koja je slika dana. Ovdje pronađete zanimljive slike koje inače ne biste vidjeli. Uživajte!</h3>

    <hr>

    <h2 id="about_title" class="margin_10_px">O nama:</h2>
    <h3 id="about_content" class="margin_10_px"><?php echo get_about_us(); ?></h3>

    <hr>

    <h2 id="collections_title" class="margin_10_px">Skupovi slika:</h2>
    <div id="collection_container">
    </div>

    <hr>
    <footer class="margin_10_px" onclick="window.location = 'https://github.com/lomjek/my-Little-Photo-Website'">This is Version 3.2 BETA of the Image Website</footer>
</body>

</html>