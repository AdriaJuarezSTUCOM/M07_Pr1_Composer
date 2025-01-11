<?php

namespace Src\Service;

require_once __DIR__ . '/../../vendor/autoload.php';

use Exception;
use Intervention\Image\ImageManager;
use Intervention\Image\Typography\FontFactory;

use Src\Model\Reparation;

class serviceReparation{
    private $mysqli;

    public function __construct() {
        $this->connect();
    }

    function connect(): void {
        $configFile = '../../cfg/db_config.ini';
        $dbParams = parse_ini_file($configFile, true)['params_db_sql'];

        if (!$dbParams || !isset($dbParams['host'], $dbParams['user'], $dbParams['pwd'], $dbParams['db_name'])) {
            throw new Exception("Error al leer el archivo de configuración.");
        }

        $this->mysqli = new \mysqli(
            $dbParams['host'],
            $dbParams['user'],
            $dbParams['pwd'],
            $dbParams['db_name'],
            $dbParams['port']
        );

        if ($this->mysqli->connect_error) {
            throw new Exception("Error de conexión: " . $this->mysqli->connect_error);
        }
    }
    
    public function insertReparation($uuid, $workshopId, $workshopName, $registerDate, $licensePlate, $photo): bool {
        try{
            // Preparar la consulta SQL para insertar los datos
            $stmt = $this->mysqli->prepare("
                INSERT INTO reparation (uuid, workshopId, workshopName, registerDate, licensePlate, photo)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param('sssssb', $uuid, $workshopId, $workshopName, $registerDate, $licensePlate, $photo);
        
            // $stmt->send_long_data(5, $photo);

            $stmt->execute();
            $stmt->close();

            return true;
        }catch(Exception){
            return false;
        }
    }
    
    
    public function getReparation($role, $idReparation){
        try{
            $stmt = $this->mysqli->prepare("SELECT * FROM reparation WHERE uuid = ?");
            $stmt->bind_param('s', $idReparation);
            $stmt->execute();
            
            // Obtener el resultado
            $result = $stmt->get_result();
            $stmt->close();

            // Verificar si se encontró un resultado
            if ($result->num_rows === 0) {
                return null;
            }

            $row = $result->fetch_assoc();

            $reparation = new Reparation(
                $row["uuid"],
                $row["workshopId"],
                $row["workshopName"],
                $row["registerDate"],
                $row["licensePlate"],
                $row["photo"]
            );

            // Mask photo if client
            if ($role == "client" && $reparation->getImage()) {
                try{
                    // Decodificar la imagen base64
                    $image = base64_decode($reparation->getImage());
        
                    // Crear una instancia de la imagen usando Intervention Image
                    $img = ImageManager::gd()->read($image);
        
                    // Agregar la marca de agua con texto
                    $img->text($reparation->getLicensePlate() . $reparation->getUuid(), 120, 100, function ($font) {
                        $font->size(30);
                        $font->color('black');
                        $font->align('center');
                        $font->valign('middle');
                        $font->angle(10);
                    });

                    // Agregar el pixelado
                    $img->pixelate(10);
                    
                    $img = base64_encode($img->encode());
        
                    // Establecer la nueva imagen con el pixelado en base64
                    $reparation->setImage($img);
                }catch(Exception $e){
                    echo $e->getMessage();
                }
            }else if ($role == "employee" && $reparation->getImage()) {
                try{
                    // Decodificar la imagen base64
                    $image = base64_decode($reparation->getImage());
        
                    // Crear una instancia de la imagen usando Intervention Image
                    $img = ImageManager::gd()->read($image);
        
                    // Agregar la marca de agua con texto
                    $img->text($reparation->getLicensePlate() . $reparation->getUuid(), 120, 100, function ($font) {
                        $font->size(30);
                        $font->color('black');
                        $font->align('center');
                        $font->valign('middle');
                        $font->angle(10);
                    });
                    
                    $img = base64_encode($img->encode());
        
                    // Establecer la nueva imagen con la marca de agua en base64
                    $reparation->setImage($img);
                }catch(Exception $e){
                    echo $e->getMessage();
                }
            }

            return $reparation;

        }catch(Exception){
            return null;
        }
    }
}