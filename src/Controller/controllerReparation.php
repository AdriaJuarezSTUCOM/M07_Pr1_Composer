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

if(isset($_POST["insertReparation"])){
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
        if (isset($_POST['workshopId'], $_POST['workshopName'], $_POST['registerDate'], $_POST['licensePlate'], $_FILES['photo'])) {
            $uuid = Uuid::uuid4()->toString();
            $workshopId = $_POST['workshopId'];
            $workshopName = $_POST['workshopName'];
            $registerDate = $_POST['registerDate'];
            $licensePlate = $_POST['licensePlate'];
            
            // Verificar si se subió una imagen y no hubo errores
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                // Leer el contenido binario de la imagen
                $photo = file_get_contents($_FILES['photo']['tmp_name']);
                $photo = base64_encode($photo);
            } else {
                error_log("Error al cargar la imagen: " . $_FILES['photo']['error']);
            }
        

            $service = new ServiceReparation();
            $view = new ViewReparation();
            if($service->insertReparation($uuid, $workshopId, $workshopName, $registerDate, $licensePlate, $photo)){
                $view->renderMessage("Reparation has been created correctly");
            }else{
                $view->renderMessage("ERROR: Reparation hasn't been created");
            }
        }
    }
}