<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

if (isset($_POST['order']) && is_string($_POST['order'])) {
    $orderArray = explode(',', $_POST['order']);
    foreach ($orderArray as &$element) {
        $element = trim($element);
    }
}

file_put_contents("../photos/order.csv", implode(', ', $orderArray));

echo 0;
exit;

?>