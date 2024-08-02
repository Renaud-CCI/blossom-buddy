<?php

namespace App\Repositories;

use App\Models\Plant;
use App\Repositories\PlantRepositoryInterface;


class PlantRepository implements PlantRepositoryInterface
{
    /**
     * Get all plants
     * @return array
     */
    public function index(): array
    {
        return Plant::all()->toArray();
    }

    /**
     * Create a new plant
     * @param array $data
     * @return Plant
     */
    public function create(array $data): Plant
    {
        return Plant::create($data);
    }

    /**
     * Find a plant by id
     * @param int $id
     * @return Plant|null
     */
    public function findById(int $id): ?Plant
    {
        return Plant::find($id);
    }

    /**
     * Find a plant by name
     * @param string $name
     * @return Plant|null
     */
    public function findByName(string $name): ?Plant
    {
        return Plant::where('common_name', $name)->first();
    }

    /**
     * Find a plant by name and benchmark
     * @param string $name
     * @param string $benchmark
     * @return Plant|null
     */
    public function findOrCreateByNameAndBenchmark($name, $benchmark): Plant
    {
        $plant = Plant::where('common_name', $name)->where('watering_general_benchmark', $benchmark)->first();
        if (!$plant) {
            $plant = Plant::create(['common_name' => $name, 'watering_general_benchmark' => $benchmark]);
        }
        return $plant;
    }

    /**
     * Update a plant
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $plant = Plant::find($id);

        if (!$plant) {
            return false;
        }

        return $plant->update($data);
    }

    /**
     * Delete a plant
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $plant = Plant::find($id);

        if (!$plant) {
            return false;
        }

        return $plant->delete();
    }
}