<?php

namespace Src\Controller;

require_once __DIR__ . '/../../vendor/autoload.php';

use Src\Service\ServiceReparation;
use Src\View\ViewReparation;
use Ramsey\Uuid\Uuid;

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
        if(isset($_GET["uuid"])){
            $idReparation = $_GET["uuid"];
        }
        $role = $_SESSION["role"];
    
        $service = new ServiceReparation();
        $reparation = $service->getReparation($role, $idReparation);

        $view = new ViewReparation();
        if($reparation !== null){
            $view->renderReparation($reparation);
        }else{
            $view->renderMessage("ERROR: That reparation does not exist");
        }
    }
    
    function insertReparation(): void{
        if (isset($_GET['workshopId'], $_GET['workshopName'], $_GET['registerDate'], $_GET['licensePlate'])) {
            $uuid = Uuid::uuid4()->toString();
            $workshopId = $_GET['workshopId'];
            $workshopName = $_GET['workshopName'];
            $registerDate = $_GET['registerDate'];
            $licensePlate = $_GET['licensePlate'];
        }

        $service = new ServiceReparation();
        $view = new ViewReparation();
        if($service->insertReparation($uuid, $workshopId, $workshopName, $registerDate, $licensePlate)){
            $view->renderMessage("Reparation has been created correctly");
        }else{
            $view->renderMessage("ERROR: Reparation hasn't been created");
        }
    }
}