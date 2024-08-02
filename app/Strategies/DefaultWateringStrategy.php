<?php

namespace App\Strategies;

use App\Interfaces\IWateringStrategy;
use DateTime;
use Illuminate\Http\JsonResponse;
use App\Services\WeatherService;

class DefaultWateringStrategy implements IWateringStrategy
{
    private WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

     /**
     * Calculate the day until the next watering
     * @param Date $doNotWaterBefore
     * @param string $userPlantCity
     * @return JsonResponse
     */
    public function calculateDayUntilNextWatering(DateTime $doNotWaterBefore, string $userPlantCity): JsonResponse
    {
        $today = new DateTime();
        
        $precipitations = json_decode($this->weatherService->showCityWeather($userPlantCity)->getContent(), true);
        $wateringAlert = 30; // Valeur en mm de pluies cumulées en dessous de laquelle il faudra arroser
    
    
        if ($today < $doNotWaterBefore) {
            return response()->json(['message' => 'Il ne faut pas arroser la plante, trop tôt'], 200);
        }
    
        $cumulativeRainfall = 0;
        foreach ($precipitations as $date => $mm) {
            $precipitationDate = new DateTime($date);
            if ($precipitationDate <= $today) {
                $cumulativeRainfall += $mm;
            }
        }
    
        if ($cumulativeRainfall < $wateringAlert) {
            foreach ($precipitations as $date => $mm) {
                $precipitationDate = new DateTime($date);
                if ($precipitationDate > $today && $mm >= 10) {
                    return response()->json(['message' => 'Il ne faut pas arroser la plante, il pleut bientôt'], 200);
                }
            }

            $waterNeeded = $wateringAlert - $cumulativeRainfall;

            if ($waterNeeded > 0 && $waterNeeded <= 5) {
                $wateringMessage = "un demi arrosoir";
            } elseif ($waterNeeded > 5 && $waterNeeded <= 10) {
                $wateringMessage = "un arrosoir";
            } elseif ($waterNeeded > 10 && $waterNeeded <= 15) {
                $wateringMessage = "un arrosoir et demi";
            } elseif ($waterNeeded > 15 && $waterNeeded <= 20) {
                $wateringMessage = "deux arrosoirs";
            } else {
                $wateringMessage = "au moins deux arrosoirs";
            }
    
            return response()->json(['message' => "Il faut arroser la plante de $wateringMessage par mètre carré"], 200);
        }
    
        return response()->json(['message' => 'Il ne faut pas arroser la plante, assez de pluie récemment'], 200);
    }
}