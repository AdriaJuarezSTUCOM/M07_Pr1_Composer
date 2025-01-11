<?php

namespace Src\Service;

require_once __DIR__ . '/../../vendor/autoload.php';

use Exception;
use Intervention\Image\ImageManager;
use Intervention\Image\Typography\FontFactory;

use Src\Model\Reparation;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class serviceReparation{
    private $mysqli;
    private $logger;

    public function __construct() {
        // Configurar el logger
        $this->logger = new Logger('reparation_logger');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../../logs/app_workshop.log'));

        $this->connect();
    }

    function connect(): void {
        try{
            $configFile = '../../cfg/db_config.ini';
            $dbParams = parse_ini_file($configFile, true)['params_db_sql'];
    
            if (!$dbParams || !isset($dbParams['host'], $dbParams['user'], $dbParams['pwd'], $dbParams['db_name'])) {
                // Log para la falta de parámetros de configuración
                $this->logger->error("Error al leer el archivo de configuración.");
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
                // Log de error en la conexión
                $this->logger->error("Error de conexión: " . $this->mysqli->connect_error);
                throw new Exception("Error de conexión: " . $this->mysqli->connect_error);
            }
        }catch(Exception $e){
            $this->logger->error("Error al intentar establecer conexión con la base de datos.");
        }
    }
    
    public function insertReparation($uuid, $workshopId, $workshopName, $registerDate, $licensePlate, $photo): bool {
        try{
            // Preparar la consulta SQL para insertar los datos
            $stmt = $this->mysqli->prepare("
                INSERT INTO reparation (uuid, workshopId, workshopName, registerDate, licensePlate, photo)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            $stmt->bind_param('ssssss', $uuid, $workshopId, $workshopName, $registerDate, $licensePlate, $photo);
            // $stmt->send_long_data(5, $photo);

            $stmt->execute();
            $stmt->close();

            // Log de inserción exitosa
            $this->logger->info("Reparación con UUID $uuid insertada correctamente.");

            return true;
        }catch(Exception $e){
            // Log de error en la inserción
            $this->logger->error("Error al insertar la reparación con UUID $uuid: " . $e->getMessage());
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
                // Log de error si no se encuentra la reparación
                $this->logger->warning("No se encontró la reparación con UUID $idReparation.");
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

                    // Agregar el pixelado
                    $img->pixelate(40);

                    // Agregar la marca de agua con texto
                    $img->text($reparation->getLicensePlate() . $reparation->getUuid(), 950, 75, function ($font) {
                        $font->file(__DIR__ . "/../../resources/fonts/Arial.ttf");
                        $font->size(70);
                        $font->color('black');
                        $font->align('center');
                        $font->valign('middle');
                    });
                    
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
                    $img->text($reparation->getLicensePlate() . $reparation->getUuid(), 950, 75, function ($font) {
                        $font->file(__DIR__ . "/../../resources/fonts/Arial.ttf");
                        $font->size(70);
                        $font->color('black');
                        $font->align('center');
                        $font->valign('middle');
                    });
                    
                    $img = base64_encode($img->encode());
        
                    // Establecer la nueva imagen con la marca de agua en base64
                    $reparation->setImage($img);
                }catch(Exception $e){
                    echo $e->getMessage();
                }
            }

            // Log de éxito en la consulta
            $this->logger->info("Reparación con UUID $idReparation encontrada.");
            
            return $reparation;

        }catch(Exception $e){
            // Log de error en la consulta
            $this->logger->error("Error al consultar la reparación con UUID $idReparation: " . $e->getMessage());
            return null;
        }
    }
}