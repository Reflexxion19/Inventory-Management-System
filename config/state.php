<?php

session_save_path("/tmp");
session_start();

if(isset($_POST['inventory_tab_state'])) {
    $_SESSION['inventory_tab_state'] = $_POST['inventory_tab_state'];
}

?>