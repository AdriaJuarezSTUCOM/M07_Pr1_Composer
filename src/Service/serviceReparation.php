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
    
    public function insertReparation(){
        
    }
    
    public function getReparation($role, $idReparation): Reparation{
        $stmt = $this->mysqli->prepare("SELECT * FROM reparation WHERE uuid = ?");
        $stmt->bind_param('s', $idReparation);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $reparation = new Reparation(
            $result["uuid"],
            $result["workshopId"],
            $result["workshopName"],
            $result["registerDate"],
            $result["licensePlate"]
        );

        //mask photo if client
        if($role == "client"){

        }

        return $reparation;
    }
}