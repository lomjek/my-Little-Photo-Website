<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

require $_SERVER['DOCUMENT_ROOT'] . '/data/server.php';
require $_SERVER['DOCUMENT_ROOT'] . '/libs/iod.php';
$order = get_collections();
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Lovro Leon Photos</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="data/style.css">
        <script src="index.js" defer></script>
    </head>
    <body id="main">
        <h3 onclick="window.location.assign('update')" id="open_update">Dodaj Slike =></h3>
        
		<h1 id="Title">Lovro Leon Slike</h1>
        <?php if (is_maintenance()) { echo '<h3 id="maintenance">Ima rade na serveru, tako da je moguče, da u sljedeče vrijeme naletite na probleme.</h3>'; } ?>

        <hr>
        <h2 id="iod_title">Slika dana:</h2>
		<div>
			<h3 style="float: right;" id="iod_link">Otvori u Skupu =></h3>
            <h3 id="iod_description">Ovdje ćete svaki dan vidjeti drugu sliku, koja je slika dana. Ovdje pronađite zanimljive slike koje inače ne biste vidjeli. Ove slike odabira programm, a može se tjekom dana isto promjeniti, ako se dodava slike ovoga dana. Uživajte!</h3>
			<img src="<?php echo get_iod(); ?>" id="iod_img" alt="The Image of the Day could not be loaded.">
		</div>
        <hr>
        <h2 id="onama">O nama:</h2>
        <h3><?php echo get_about_us(); ?></h3>
        <hr>
		<h2 id="skupovi">Skupovi slika:</h2>
		<div>
			<?php display_collections($order); ?>
		</div>
        
        <hr>
        <footer>This is Version 3.0.0 of the Image Website</footer>
    </body>
</html>
    
