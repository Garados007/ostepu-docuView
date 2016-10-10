<?php
    
//echo "<pre>";var_dump($_SERVER);echo "</pre>";
//return;

//Überprüfe, ob ein Datensatz existiert
if (!is_file(__DIR__."/../data/index.json")) {
    //Daten existieren nicht und müssen vorher erstellt werden.
    $url = $_SERVER["PHP_SELF"];
    $url = explode("/", $url);
    unset($url[count($url)-1]);
    $url[] = "Error.php";
    header("Location: ".implode("/", $url), TRUE, 307);
    return;
}

//Restliche Überprüfungen

//Methoden zur Anzeige importieren
include_once ("functions.php");

//Anzeige ausgeben
include_once ("page.php");