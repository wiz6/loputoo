<?php
function autoloadController($className) {
    $filename = "controller/" . $className . ".php";
    if (is_readable($filename)) {
        require $filename;
    }
}

function autoloadModel($className) {
    $filename = "model/" . $className . ".php";
    if (is_readable($filename)) {
        require $filename;
    }
}

function autoloadDao($className) {
    $filename = "dao/" . $className . ".php";
    if (is_readable($filename)) {
        require $filename;
    }
}

spl_autoload_register("autoloadController");
spl_autoload_register("autoloadModel");
spl_autoload_register("autoloadDao");

