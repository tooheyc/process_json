<?php

require "./routes/routes.php";

spl_autoload_register(function ($class_name) {
    include "./classes/".$class_name . '.php';
});

$route = new routes();
