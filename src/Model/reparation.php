<?php

namespace Src\Model;

require_once __DIR__ . '/../../vendor/autoload.php';

class reparation{
    private string $uuid;
    private int $workshopId;
    private string $workshopName;
    private $registerDate;
    private string $licensePlate;
    private $image;

    public function __construct($uuid, $workshopId, $workshopName, $registerDate, $licensePlate){
        $this->uuid = $uuid;
        $this->workshopId = $workshopId;
        $this->workshopName = $workshopName;
        $this->registerDate = $registerDate;
        $this->licensePlate = $licensePlate;
    }

    public function getworkshopName(): string{
        return $this->workshopName;
    }

    public function getworkshopId(): int{
        return $this->workshopId;
    }

    public function getName(): string{
        return $this->workshopName;
    }

    public function getregisterDate(): string{
        return $this->registerDate;
    }

    public function getLicensePlate(): string{
        return $this->licensePlate;
    }

    public function getImage(){
        return $this->image;
    }

    public function getUuid(): string{
        return $this->uuid;
    }

    public function setWorkshopName(int $workshopName): void{
        $this->workshopName = $workshopName;
    }

    public function setWorkshopId(int $workshopId): void{
        $this->workshopId = $workshopId;
    }
    
    public function setName(string $workshopName): void{
        $this->workshopName = $workshopName;
    }
    
    public function setRegisterDate(string $registerDate): void{
        $this->registerDate = $registerDate;
    }
    
    public function setLicensePlate(string $licensePlate): void{
        $this->licensePlate = $licensePlate;
    }
    
    public function setImage($image): void{
        $this->image = $image;
    }
    
    public function setUuid($uuid): void{
        $this->uuid = $uuid;
    }
}