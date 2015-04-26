<?php

if (empty($_GET['action'])) {
    die();
}

require_once('setup.php');
$controller = new EventController($_REQUEST);
$controller->doAction($_GET['action']);

