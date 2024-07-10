<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use App\Repositories\PlantRepositoryInterface;
use App\Repositories\PlantRepository;
use App\Services\PlantApiServiceInterface;
use App\Services\PerenualApiService;

class PlantService {

    private PlantRepositoryInterface $plantRepository;
    private PlantApiServiceInterface $plantApiService;

    public function __construct(PlantRepository $plantRepository, PerenualApiService $plantApiService)
    {
        $this->plantRepository = $plantRepository;
        $this->plantApiService = $plantApiService;
    }

    public function updatePlants(): void
    {
        $plants = $this->plantApiService->getAll();
        foreach ($plants['data'] as $plantData) {
            $plantDetails = $this->plantApiService->getPlant($plantData['id']);
            
            if (!isset($plantDetails['common_name'], $plantDetails['watering_general_benchmark'], $plantDetails['default_image']['small_url'])) {
                continue;
            }

            $data = [
                'common_name' => $plantDetails['common_name'],
                'watering_general_benchmark' => json_encode($plantDetails['watering_general_benchmark']),
                'image' => $plantDetails['default_image']['small_url'],
            ];
            
            // Vérifie si plante existe et créé ou update
            $existingPlant = $this->plantRepository->findByName($plantDetails['common_name']);
            if ($existingPlant) {
                $this->plantRepository->update($existingPlant->id, $data);
            } else {
                $this->plantRepository->create($data);
            }
        }
    }
}