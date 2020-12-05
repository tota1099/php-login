
<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once(__DIR__."/vendor/autoload.php");
    require_once(__DIR__."/src/domain/model/model.php");
    require_once(__DIR__."/src/domain/usecases/usecases.php");
    require_once(__DIR__."/src/data/interfaces/interfaces.php");
    require_once(__DIR__."/src/data/usecases/usecases.php");