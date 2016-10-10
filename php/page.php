<?php

?>

<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8" />
        <title><?echo getTitle();?></title>
        <?echo getImportList();?>
    </head>
    <body>
        <header>
            <h1>OSTEPU Entwickler Hilfe</h1>
            <form class="search-bar" method="post">
                <input type="text" name="query" placeholder="Suche" />
            </form>
        </header>
        <div class="content-helper">
            <nav>
                <?echo createNavigation();?>
            </nav>
            <div class="content"></div>
        </div>
    </body>
</html>
