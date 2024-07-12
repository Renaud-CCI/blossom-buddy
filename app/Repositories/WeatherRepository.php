<?php

namespace App\Repositories;

use App\Models\Weather;
use App\Repositories\WeatherRepositoryInterface;


class WeatherRepository implements WeatherRepositoryInterface
{
    public function getAll(): array
    {
        return Weather::all();
    }

    public function getById(int $id): Weather
    {
        return Weather::find($id);
    }

    public function getByCity(string $city): ?Weather
    {
        return Weather::where('city', $city)->first();
    }

    public function create(array $data): Weather
    {
        return Weather::create($data);
    }

    public function update(int $id,array $data): ?Weather
    {
        $weather = Weather::find($id);
        if ($weather) {
            $weather->update($data);
            return $weather;
        }
        return null;
    }

    public function delete(int $id): bool
    {
        $weather = Weather::find($id);
        if ($weather) {
            $weather->delete();
            return true;
        }
        return false;
    }

    public function updateOrCreate(array $city, array $data): Weather
    {
        return Weather::updateOrCreate($city, $data);
    }
}