<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use App\Interfaces\IPlantRepository;
use App\Repositories\PlantRepository;
use App\Services\PlantApiServiceInterface;
use App\Services\PerenualApiService;

class PlantService {

    private IPlantRepository $plantRepository;
    private PlantApiServiceInterface $plantApiService;

    public function __construct(PlantRepository $plantRepository, PerenualApiService $plantApiService)
    {
        $this->plantRepository = $plantRepository;
        $this->plantApiService = $plantApiService;
    }

    /**
     * Get Api plants and store them in DB
     */
    public function updatePlants(): void
    {
        // Récupère toutes les plantes de la page 1
        $plants = $this->plantApiService->getAll();

        // Update ou enregistre les plantes en DB
        $this->storePlantsInDb($plants['data']);
    }

    /**
     * Store plants in DB
     * @param array $plantsDatas
     */
    public function storePlantsInDb(array $plantsDatas): void
    {
        foreach ($plantsDatas as $plantData) {
            // Récupère les détails de la plante
            $plantDetails = $this->plantApiService->getPlant($plantData['id']);
            
            // Vérifie si les données sont complètes
            if (!isset($plantDetails['common_name'], $plantDetails['watering_general_benchmark'], $plantDetails['default_image']['small_url'])) {
                continue;
            }

            // Crée un tableau de données
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