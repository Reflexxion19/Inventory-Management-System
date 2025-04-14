<?php

if(isset($_POST['name'])){
    $name = $_POST['name'];
    $data = $_POST['data'];
    echo "name: " . $name . "; data: ". $data;
}

?>