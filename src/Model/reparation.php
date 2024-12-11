<?php

namespace Src\Model;

class Reparation{
    private int $workshopId;
    private string $workshopName;
    private $registerDate;
    private string $licensePlate;
    private $image;
    private $uuid;

    public function __construct($workshopId, $workshopName, $registerDate, $licensePlate, $uuid){
        $this->workshopId = $workshopId;
        $this->workshopName = $workshopName;
        $this->registerDate = $registerDate;
        $this->licensePlate = $licensePlate;
        $this->uuid = $uuid;
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

    public function getUuid(){
        return $this->uuid;
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