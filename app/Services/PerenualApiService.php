<?php

namespace App\Services;

use App\Services\PlantApiServiceInterface;

class PerenualApiService implements PlantApiServiceInterface
{
    private string $apiUrl = 'https://perenual.com/api';
    private string $apiKey;
    
    public function __construct()
    {
        $this->apiKey = env('PERENUAL_API_KEY');
    }

    public function getAll(): array
    {
        $curl = curl_init($this->apiUrl . '/species-list?key=' . $this->apiKey);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        // Enregistrement de la réponse pour tests
        $directoryPath = storage_path('app/public/draft');
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        }
        $filePath = $directoryPath . '/getPlantResponse.json';
        file_put_contents($filePath, $response);
    
        return json_decode($response, true);
    }

    public function getPlant(int $plantId): array
    {
        $curl = curl_init($this->apiUrl . '/species/details/' . $plantId . '?key=' . $this->apiKey);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }
}