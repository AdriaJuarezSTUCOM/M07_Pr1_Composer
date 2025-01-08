<?php

namespace Src\Service;

require_once __DIR__ . '/../../vendor/autoload.php';

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
            throw new \Exception("Error al leer el archivo de configuración.");
        }

        $this->mysqli = new \mysqli(
            $dbParams['host'],
            $dbParams['user'],
            $dbParams['pwd'],
            $dbParams['db_name'],
            $dbParams['port']
        );

        if ($this->mysqli->connect_error) {
            throw new \Exception("Error de conexión: " . $this->mysqli->connect_error);
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
        
            $stmt->execute();
            $stmt->close();

            return true;
        }catch(\Exception){
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
            if ($role == "client") {
            }

            return $reparation;

        }catch(\Exception){
            return null;
        }
    }
}