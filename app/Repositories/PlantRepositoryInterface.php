<?php
namespace App\Repositories;

use App\Models\Plant;

interface PlantRepositoryInterface
{
    public function index(): array;

    public function create(array $data): Plant;

    public function findById(int $id): ?Plant;

    public function findByName(string $name): ?Plant;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}