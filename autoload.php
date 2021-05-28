<?php


spl_autoload_register(function($nombre_clase) {

    $file = __DIR__ . DIRECTORY_SEPARATOR . 'clases' . DIRECTORY_SEPARATOR. str_replace('\\', DIRECTORY_SEPARATOR, $nombre_clase) . ".php";

    if ($file != "") {
       
        if (file_exists($file)) {
           //  echo $file;
            include $file;
        }
    }
});