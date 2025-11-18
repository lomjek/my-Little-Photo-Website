<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

function collection_exists($array, $element) {
    foreach ($array as $index => $childArray) {
        if (isset($childArray[0])) {
            if (trim($childArray[0]) === trim($element)) {
                return $index + 1;
            }
        }
    }
    return false;
}


if (isset($_GET['c']) && is_string($_GET['c'])) {
    $cname = $_GET['c'];

    ob_start();
    include '../../loading.php';
    $output = ob_get_clean();
    $array = explode(PHP_EOL, $output);

    $array2 = [];
    foreach ($array as $element) {
        $splitElement = explode(':', $element);
        $array2[] = $splitElement;
    }

    $response = collection_exists($array2, $cname);
    if (!$response){
        header("Location: /update/collection/");
    }

    $ourarray = $array2[$response - 1];

    $title = "Modify " . $cname;
    $rename = true;
    $cdate = $ourarray[2];
    $bcol = $ourarray[3];
    $tcol = $ourarray[4];
    
} else {
    $title = "Add a new Collection";
    $cname = "";
    $cdate = "";
    $bcol = "#aaaaaa";
    $tcol = "#333333";
    $output = "";
    $rename = false;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo str_replace('-', ' ', htmlspecialchars($title)); ?></title>
        <style>
            @font-face {
                font-family: Nirmala;
                src: url(../../Nirmala.ttc);
            }
            body {
                background-color: <?php echo $bcol; ?>;
            }
            input, label {
                font-size: 3em;
                color: <?php echo $tcol; ?>;
            }
            h1 {
                text-align: center;
                color: <?php echo $tcol; ?>;
                font-size: 100px;
                font-family: Nirmala;
            }
            input {
                width: 100%;
                background-color: #aaa;
            }
            label, input{
                font-family: Nirmala;
            }
            tr {
                border-bottom: 10px groove #333 !important;
            }
            tr:last-child {
                border-bottom: none !important;
            }
            table {
                border-collapse: collapse;
            }
            h2 {
                font-size: 40px;
                color: <?php echo $tcol; ?>;
                text-align: center;
                font-family: Nirmala;
            }
            hr {
                height: 5px;
                border: 0px;
                background-color: <?php echo $tcol; ?>;
            }
            #middle {
                border-left: 5px solid <?php echo $tcol; ?>;
                border-right: 5px solid <?php echo $tcol; ?>;
            }
        </style>
        <script>
            function change_bg_col(){
                document.body.style.setProperty("background-color", document.getElementById("bcol").value, "important");
            }
            function change_t_col(){
                var inputs = document.querySelectorAll('input');
                inputs.forEach(function(input) {
                    input.style.setProperty("color", document.getElementById("tcol").value, "important");
                });
                var labels = document.querySelectorAll('label');
                labels.forEach(function(label) {
                    label.style.setProperty("color", document.getElementById("tcol").value, "important");
                });
                document.getElementById("Title").style.setProperty("color", document.getElementById("tcol").value, "important");
            }
            function sure(){
                const confirmation = confirm("Are you sure you want to proceed?\nThe collection will be deleted and all the images in it as well...");

                if (confirmation) {
                    return true;
                } else {
                    return false;
                }
            }
        </script>
    </head>
    <body>
        <h1 id="Title">Add a new Collection</h1>

        <div style="background-color: #808080 !important;"> <!--This is the menu div.-->
            <hr>
            <table style="padding: 0 !important; background-color: #808080;">
                <tr>
                    <td style="width:25%;" onclick="window.location.assign('../../')"><h2>Home</h2></td>
                    <td style="width:25%;" onclick="window.location.assign('../')" id="middle"><h2>Main Update Page</h2></td>
                    <?php
                    if (isset($_GET['c'])){
                        echo '<td style="width:25%;" onclick="window.location.assign(\'../images/\')"><h2>Add Images</h2></td>';

                    } else {
                        echo '<td style="width:25%;" onclick="window.location.assign(\'../../iod\')"><h2>Image of the Day</h2></td>';
                    }
                    ?>
                </tr>
            </table>
            <hr>
        </div>
        <br><br>

        <form id="form" action="../collections" method="post" enctype="application/x-www-form-urlencoded">
            <table style="border-spacing: 100px 100px !important;">
                <tr>
                    <td><label for="cname">Name of the Collection:</label></td>
                    <td><input type="text" name="cname" id="cname" value="<?php echo str_replace("-", " ", htmlspecialchars($cname)); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="cdate">Date of the collection (will not be displayed if left empty):</label></td>
                    <td><input type="date" name="cdate" id="cdate" value="<?php echo $cdate; ?>"></td>
                </tr>

                <tr>
                    <td><label for="bcol">Background Color of the Collection:</label></td>
                    <td><input onchange="change_bg_col()" id="bcol" type="color" name="bcol" value="<?php echo $bcol; ?>" required></td>
                </tr>

                <tr>
                    <td><label for="tcol">Text Color of the Collection:</label></td>
                    <td><input onchange="change_t_col()" type="color" name="tcol" id="tcol" value="<?php echo $tcol; ?>" required ></td>
                </tr>
                <input type="hidden" name="rename" value="<?php echo htmlspecialchars($rename); ?>">
                <input type="hidden" name="oldname" value="<?php echo htmlspecialchars($cname); ?>">
                <tr><td><input type="submit"></td></tr>
            </table>
        </form>
        <br><br>
        <form onsubmit="return sure()" action="../delete/collection/" method="POST">
            <input type="hidden" name="c" value="<?php echo $_GET['c']; ?>">
            <?php
                if (isset($_GET['c'])){
                    echo "<input type='submit' value='DELETE'>";
                }
            ?>
        </form>
    </body>
</html>
