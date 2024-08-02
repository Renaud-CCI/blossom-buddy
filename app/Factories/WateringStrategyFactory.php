<?php
namespace App\Factories;

use App\Interfaces\IWateringStrategy;
use App\Strategies\DefaultWateringStrategy;
use App\Strategies\IndoorPlantWateringStrategy;
use App\Services\WeatherService;

class WateringStrategyFactory
{
    private WeatherService $weatherService;

    public function __construct(WeatherService $weatherService,)
    {
        $this->weatherService = $weatherService;
    }

    public function create(bool $isOutdoor): IWateringStrategy
    {
        $plantType = $isOutdoor ? 'outdoor' : 'indoor';
        switch ($plantType) {
            case 'indoor':
                return new IndoorPlantWateringStrategy();
            case 'outdoor':
            default:
                return new DefaultWateringStrategy($this->weatherService);
        }
    }
}