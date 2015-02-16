<?php

require_once "src/App.php";
require_once "src/Route.php";

function __autoload($class_name) {
    require_once "controllers/". $class_name . '.php';
}