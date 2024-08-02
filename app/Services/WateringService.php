<?php

namespace App\Services;

use DateTime;
use Illuminate\Http\JsonResponse;
use App\Services\UserPlantService;
use App\Models\UserPlant;
use App\Factories\WateringStrategyFactory;

class WateringService
{
    protected UserPlantService $userPlantService;
    private WateringStrategyFactory $wateringStrategyFactory;

    public function __construct(UserPlantService $userPlantService, WateringStrategyFactory $wateringStrategyFactory)
    {
        $this->userPlantService = $userPlantService;
        $this->wateringStrategyFactory = $wateringStrategyFactory;
    }

    public function wateringStrategy(UserPlant $userPlant): JsonResponse
    {
        $doNotWaterBefore = $this->userPlantService->getWateringDelay($userPlant);

        return $this->wateringStrategyFactory->create($userPlant->is_outdoor)->calculateDayUntilNextWatering($doNotWaterBefore, $userPlant->city);
    }

    public function convertWatering(int $waterNeeded): JsonResponse
    {
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

}