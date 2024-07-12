<?php

namespace App\Services;

use App\Repositories\WeatherRepositoryInterface;
use App\Repositories\WeatherRepository;
use App\Services\WeatherApiServiceInterface;
use App\Services\WeatherApiService;
use App\Services\VarDumpService;
use Illuminate\Http\JsonResponse;

class WeatherService {

    private WeatherRepositoryInterface $weatherRepository;
    private WeatherApiServiceInterface $weatherApiService;
    private VarDumpService $varDumpService;

    public function __construct(WeatherRepository $weatherRepository, WeatherApiService $weatherApiService, VarDumpService $varDumpService)
    {
        $this->weatherRepository = $weatherRepository;
        $this->weatherApiService = $weatherApiService;
        $this->varDumpService = $varDumpService;
    }

    /**
     * Show the weather for a city
     *
     * @param string $city
     * @return jsonResponse
     */
    public function showCityWeather(string $city): jsonResponse
    {
        $weather = $this->weatherRepository->getByCity($city);
        $datesNeeded = $this->generateDateRange(-3, 2);
    
        if ($weather && $this->checkDateCoverage($weather->precipitations, $datesNeeded)) {
            return response()->json($weather);
        } else {
            $weatherData = $this->getCityWeather($city);
            $weatherJson = json_decode($weatherData->getContent(), true);
            
            if (!isset($weatherJson['error'])) {
                $this->weatherRepository->updateOrCreate(
                    ['city' => $city],
                    ['precipitations' => $weatherData]
                );
            }
    
            return $weatherData;
        }
    }

    /**
     * Generate an array of dates from $startDays to $endDays
     *
     * @param int $startDays
     * @param int $endDays
     * @return array
     */
    private function generateDateRange(int $startDays, int $endDays): array
    {
        $range = [];
        for ($i = $startDays; $i <= $endDays; $i++) {
            $range[] = date('Y-m-d', strtotime("$i days"));
        }
        return $range;
    }

    /**
     * Check if the weather data covers the needed dates
     *
     * @param string $precipitations
     * @param array $datesNeeded
     * @return bool
     */
    private function checkDateCoverage($precipitations, array $datesNeeded): bool
    {
        foreach ($datesNeeded as $date) {
            if (!array_key_exists($date, $precipitations)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the weather data for a city
     *
     * @param string $city
     * @return jsonResponse
     */
    public function getCityWeather(string $city): jsonResponse
    {
        $pastWeather = $this->getPastCityWeather($city);
        $presentWeather = $this->getPresentCityWeather($city);
    
        if (isset($pastWeather['error']) || isset($presentWeather['error'])) {
            return response()->json(['error' => 'An error occurred fetching weather data.']);
        }
    
        $combinedWeather = array_merge($pastWeather, $presentWeather);
        ksort($combinedWeather); // Tri des résultats par date
    
        return response()->json($combinedWeather);
    }

    /**
     * Get the present weather data for a city
     *
     * @param string $city
     * @return array
     */
    private function getPresentCityWeather(string $city): array
    {
        $results = [];
        $response = $this->weatherApiService->getPresentCityWeather($city);
        if (isset($response['error'])) {
            $results['error'] = 'An error occurred fetching present weather data.';
            return $results;
        }

        if (isset($response['forecast']['forecastday'])) {
            foreach ($response['forecast']['forecastday'] as $forecastDay) {
                $date = $forecastDay['date'];
                $totalPrecipMm = $forecastDay['day']['totalprecip_mm'];
                $results[$date] = $totalPrecipMm;
            }
        }

        return $results;
    }

    /**
     * Get the past weather data for a city
     *
     * @param string $city
     * @return array
     */
    private function getPastCityWeather(string $city): array
    {
        $results = [];

        for ($i = 1; $i <= 3; $i++) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $pastWeather = $this->weatherApiService->getPastCityWeather($city, $date);

            if (isset($pastWeather['error'])) {
                $results['error'] = 'An error occurred fetching present weather data.';
                continue;
            }

            if (isset($pastWeather['forecast']['forecastday'][0]['day']['totalprecip_mm'])) {
                $results[$date] = $pastWeather['forecast']['forecastday'][0]['day']['totalprecip_mm'];
            }
        }
        
        return $results;
    }
}