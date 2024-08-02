<?php

namespace App\Strategies;

use App\Interfaces\IWateringStrategy;
use DateTime;
use Illuminate\Http\JsonResponse;

class IndoorPlantWateringStrategy implements IWateringStrategy
{
    /**
     * Calculate the day until the next watering
     * @param Date $doNotWaterBefore
     * @param string $userPlantCity
     * @return JsonResponse
     */
    public function calculateDayUntilNextWatering(DateTime $doNotWaterBefore, string $userPlantCity): JsonResponse
    {        
        if (new DateTime() < $doNotWaterBefore) {
            return response()->json(['message' => 'Il ne faut pas arroser la plante'], 200);
        } else {
            return response()->json(['message' => 'Il faut arroser la plante'], 200);
        }
    }
}