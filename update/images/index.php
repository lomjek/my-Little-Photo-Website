<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

require $_SERVER['DOCUMENT_ROOT'] . '/data/server.php';
$order = get_collections();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['files']) and isset($_POST['collection'])) {
        $collection = $_POST['collection'];
        $files = $_FILES['files'];
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/update/images/uploads/";
        mkdir($uploadDir . $collection);

        for ($i = 0; $i < count($files['name']); $i++) {
            $tmpName = $_FILES['files']['tmp_name'];
            $originalName = $_FILES['files']['name'];

            $fileInfo = pathinfo($originalName[$i]);
            
            $baseName = $fileInfo['filename'];
            $extension = isset($fileInfo['extension']) ? '.' . $fileInfo['extension'] : '';

            $newDestination = $uploadDir . $collection . '/' . $originalName[$i];
            $counter = 1;
            while (file_exists($newDestination)) {
                $newDestination = $uploadDir . $collection . '/' . $baseName . '_' . $counter . $extension;
                $counter++;
            }
            move_uploaded_file($tmpName[$i], $newDestination);
        }
        kill(202, 'Upload done...');
    } else {
        kill(406, 'To few arguments...');
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT'){
    ignore_user_abort(true);
    $converter = getcwd() . "/processing/processImages.py";
    if (file_exists(getcwd() . "/processing/process.lock")){
        kill(409);
    }
    exec("python3 ". $converter);
    kill(200);
} 
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Dodaj Slike</title>
        <link rel="stylesheet" href="/data/style.css">
        <script src="/update/images/main.js"></script>
        <style>
            button {
                width: 100%;
                height: clamp(0.7rem, 9vw, 5rem); 
                font-size: clamp(0.5rem, 7vw, 4rem);
            }
        </style>
    </head>
    <body>
        <h3 id="leave" onclick="window.location = '/update/'"><= Ažuriranje LLS</h3>
        <h1 id = 'Title' style="color: #333333;">Dodajte Slike</h1>
        
        <div style="margin-bottom: 20px; clear: both;"></div>

        <select id="skupovi" style="width: 50%; font-size: clamp(0.5rem, 7vw, 4rem); float: left;">
			<?php 
			foreach ($order as $collection){
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

        <button id="process" onclick="sendProcess()">Process Images</button>
    </body>
</html>
