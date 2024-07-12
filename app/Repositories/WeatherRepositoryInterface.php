<?php

namespace App\Repositories;

use App\Models\Weather;


interface WeatherRepositoryInterface
{
    public function getAll(): array;

    public function getById(int $id): Weather;

    public function getByCity(string $city): ?Weather;

    public function create(array $data): Weather;

    public function update(int $id,array $data): ?Weather;

    public function delete(int $id): bool;
}