<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

require $_SERVER['DOCUMENT_ROOT'] . '/data/server.php';
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Lovro Leon Slike</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />

        <link rel="stylesheet" href="data/style.css">
        <link rel="stylesheet" href="index.css">

        <script src="index.js" defer></script>
    </head>
    <body id="main">
        <p hidden><?php echo get_about_us(); ?></p>
        <h4 onclick="window.location.assign('update')" id="open_update">ADMIN></h4>
        
		<h1 id="Title">Lovro Leon Slike</h1>
        <?php if (is_maintenance()) { echo '<h3 id="maintenance">Ima rade na serveru, tako da je moguče, da u sljedeče vrijeme naletite na probleme.</h3>'; } ?>

        <hr>
        
        <h2 id="iod_title">Slika dana:</h2>
        <img id="iod_img" alt="The Image of the Day could not be loaded.">
        <h3 id="iod_link">Otvori Sliku></h3>
        <h3 id="iod_description">Ovdje ćete svaki dan vidjeti drugu sliku, koja je slika dana. Ovdje pronađete zanimljive slike koje inače ne biste vidjeli. Uživajte!</h3>
        
        <hr>
        
        <h2 id="about_title">O nama:</h2>
        <h3 id="about_content"><?php echo get_about_us(); ?></h3>
        
        <hr>
		
        <h2 id="collections_title">Skupovi slika:</h2>
		<div id="collection_container">
		</div>
        
        <hr>
        <footer>This is Version 3.0.0 of the Image Website</footer>
    </body>
</html>
    
