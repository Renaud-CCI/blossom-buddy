<?php

namespace App\Services;

use App\Repositories\UserPlantRepository;
use App\Models\UserPlant;
use DateTime;

class UserPlantService
{
    protected UserPlantRepository $userPlantRepository;

    public function __construct(UserPlantRepository $userPlantRepository)
    {
        $this->userPlantRepository = $userPlantRepository;
    }

    /**
     * Get the next watering date for a user plant
     * @param UserPlant $userPlant
     * @return DateTime
     */
    public function getWateringDelay(UserPlant $userPlant): DateTime
    {
        $userPlantLastWatering = new DateTime($userPlant->last_watering);
        $benchmarkValue = explode('-', json_decode($userPlant->plant->watering_general_benchmark, true)['value'])[0];
    
        return (clone $userPlantLastWatering)->modify("+$benchmarkValue days");

    }
}