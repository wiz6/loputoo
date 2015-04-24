<?php

if (empty($_GET['action'])) {
    die();
}

require_once('setup.php');
$controller = new AjaxController($_POST);
$response = $controller->doAction($_GET['action']);