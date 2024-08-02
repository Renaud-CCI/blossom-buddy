<?php
namespace App\Interfaces;

use App\Models\UserPlant;
use Illuminate\Support\Collection;

interface IUserPlantRepository
{
    public function findOrCreate(array $data): array;

    public function create(array $data): UserPlant;

    public function findByUserId($userId): Collection;

    public function findById(int $id): ?UserPlant;
}