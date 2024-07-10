<?php

namespace App\Repositories;

use App\Models\Plant;
use App\Repositories\PlantRepositoryInterface;


class PlantRepository implements PlantRepositoryInterface
{

    public function index(): array
    {
        return Plant::all()->toArray();
    }

    public function create(array $data): Plant
    {
        return Plant::create($data);
    }

    public function findById(int $id): ?Plant
    {
        return Plant::find($id);
    }

    public function findByName(string $name): ?Plant
    {
        return Plant::where('common_name', $name)->first();
    }

    public function update(int $id, array $data): bool
    {
        $plant = Plant::find($id);

        if (!$plant) {
            return false;
        }

        return $plant->update($data);
    }

    public function delete(int $id): bool
    {
        $plant = Plant::find($id);

        if (!$plant) {
            return false;
        }

        return $plant->delete();
    }
}