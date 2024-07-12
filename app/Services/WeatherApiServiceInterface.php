<?php

namespace App\Services;

interface WeatherApiServiceInterface
{
    public function getPresentCityWeather(string $city): array;

    public function getPastCityWeather(string $city, string $date): array;
}