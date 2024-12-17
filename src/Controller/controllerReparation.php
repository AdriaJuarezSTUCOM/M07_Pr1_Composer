<?php

namespace Src\Controller;

require_once __DIR__ . '/../../vendor/autoload.php';

use Src\Service\ServiceReparation;
use Src\View\ViewReparation;

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

$controller = new controllerReparation();
if(isset($_GET["getReparation"])){
    $controller->getReparation();
}

if(isset($_GET["insertReparation"])){
    $controller->insertReparation();
}

class controllerReparation{
    function getReparation(): void{
        $role = $_SESSION["role"];
        $idReparation = $_GET["uuid"];
    
        $service = new ServiceReparation();
        $reparation = $service->getReparation($role, $idReparation);
    
        $view = new ViewReparation();
        $view->render($reparation);
    }
    
    function insertReparation(): void{
    
    }
}