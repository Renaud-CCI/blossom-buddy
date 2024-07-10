<?php

namespace App\Repositories;

use App\Models\UserPlant;
use Illuminate\Support\Collection;

interface UserPlantRepositoryInterface
{
    public function findOrCreate(array $data): array;

    public function create(array $data): UserPlant;

    public function findByUserId($userId): Collection;
}