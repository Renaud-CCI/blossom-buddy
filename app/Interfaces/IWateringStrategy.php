<?php

namespace App\Interfaces;

use Illuminate\Http\JsonResponse;
use DateTime;

interface IWateringStrategy
{
    public function calculateDayUntilNextWatering(DateTime $doNotWaterBefore, string $userPlantCity): JsonResponse;
}