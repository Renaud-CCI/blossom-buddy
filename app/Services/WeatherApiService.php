<?php

namespace App\Services;

use App\Services\WeatherApiServiceInterface;

class WeatherApiService implements WeatherApiServiceInterface
{
    private string $apiUrl;
    private string $apiKey;
    
    public function __construct()
    {
        $this->apiUrl = env('WEATHER_API_URL');
        $this->apiKey = env('WEATHER_API_KEY');
    }

    /**
     * Get the weather for a city
     *
     * @param string $city
     * @return array
     */
    public function getPresentCityWeather(string $city): array
    {
        try {
            $curl = curl_init($this->apiUrl . '/forecast.json?key=' . $this->apiKey . '&q=' . $city . '&days=3&aqi=no&alerts=no');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);
    
            if ($response === false) {
                return ['error' => 'Failed to fetch data from the API.'];
            }
    
            $decodedResponse = json_decode($response, true);
            if (is_null($decodedResponse)) {
                return ['error' => 'Failed to decode JSON response.'];
            }
    
            return $decodedResponse;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Get the weather for a city on a specific date
     *
     * @param string $city
     * @param string $date
     * @return array
     */
    public function getPastCityWeather(string $city, string $date): array
    {
        try {
            $curl = curl_init($this->apiUrl . '/history.json?key=' . $this->apiKey . '&q=' . $city . '&dt=' . $date);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);
    
            if ($response === false) {
                return ['error' => 'Failed to fetch data from the API.'];
            }
    
            $decodedResponse = json_decode($response, true);
            if (is_null($decodedResponse)) {
                return ['error' => 'Failed to decode JSON response.'];
            }
    
            return $decodedResponse;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

}