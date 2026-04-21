<?php

/*******************************************************/
/*   This file is part of 'my Little Photo Website'    */
/* It is published on github under the LGPLv3 License: */
/*  https://github.com/lomjek/my-Little-Photo-Website  */
/*******************************************************/

require_once $_SERVER['DOCUMENT_ROOT'] . '/libs/images.php';

$current_collection = $_GET['s'];
$file = $_GET['f'];

if (!isset($_GET['s']) || !isset($_GET['f'])) {
	http_response_code(422);
	exit;
}

$current_collection = urldecode($current_collection);
$file = urldecode($file);

$path = '/photos/' . $current_collection . "/" . $file;
$current_description = get_image_description($current_collection, $file);
http_response_code(200);
?>

<!DOCTYPE html>
<html lang="hr">

<head>
	<title><?php echo pathinfo($file)['filename']; ?></title>

	<script src="/photo/viewer.js"></script>
	<link rel="stylesheet" href="/data/style.css">
	<link rel='stylesheet' href='/photo/viewer.css' type='text/css' />

	<meta name="description" content="<?php echo $file . ":" . $current_description; ?>">
</head>

<body>
	<div id="toolbox" role="navigation">
		<img id='back' class='tool'
			src='/photo/ArrowLeft.svg' alt="BACK"
			onclick="back_to_collection('<?php echo $current_collection; ?>')" />

		<img id='desc' class='tool'
			src='/photo/Description.svg' alt="DESCRIPTION"
			onclick="toggle_description_visibility()" />

			<div class="padding"></div>
		
			<h2><?php echo $file; ?></h2>

			<div class="padding"></div>

			<div class="extra_padding"></div>
	</div>

	<div class='container' role="main">
		<img draggable='false' id='img' src='<?php echo $path; ?>' alt='Something strange...'>
	</div>

	<div id="description_container" role="footer">
		<p id="description"><?php echo $current_description; ?></p>
	</div>
</body>

</html>