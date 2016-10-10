<?php
    $url = $_SERVER["PHP_SELF"];
    $url = explode("/", $url);
    for ($i = 0; $i <4; $i++) unset($url[count($url)-1]);
    $url[] = "install.php";
    $url = implode("/", $url);
?>

<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8" />
        <title>Keine Daten</title>
    </head>
    <body>
        Es wurden keine Daten gefunden. Bitte gehen Sie unter den Einstellungen auf den Reiter <em>Entwicklung</em> und erstellen Sie dort den Index neu.<br/>
        <a href="<? echo $url;?>">Zu den Einstellungen</a>
    </body>
</html>
