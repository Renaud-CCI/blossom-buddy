<?php

namespace App\Services;


interface PlantApiServiceInterface
{
    public function getAll(): array;
    public function getPlant(int $plantId): array;
}