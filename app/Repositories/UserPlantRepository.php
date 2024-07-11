<?php
namespace App\Repositories;

use App\Models\UserPlant;
use App\Repositories\UserPlantRepositoryInterface;
use Illuminate\Support\Collection;
use DateTime;

class UserPlantRepository implements UserPlantRepositoryInterface
{
    public function findOrCreate(array $data): array
    {
        $userPlant = UserPlant::where('user_id', $data['user_id'])
                              ->where('plant_id', $data['plant_id'])
                              ->first();
        $created = false;
    
        if (!$userPlant) {
            $userPlant = $this->create($data);
            $created = true;
        }
    
        return ['userPlant' => $userPlant, 'created' => $created];
    }

    public function create(array $data): UserPlant
    {
        $userPlant = new UserPlant();
        $userPlant->user_id = $data['user_id'];
        $userPlant->plant_id = $data['plant_id'];
        $userPlant->plant_name = $data['plant_name'];
        $userPlant->plant_title = $data['plant_title'];
        $userPlant->last_watering = new DateTime($data['last_watering']);;
        $userPlant->city = $data['city'];
        $userPlant->is_outdoor = $data['is_outdoor'];
        $userPlant->save();

        return $userPlant;
    }

    public function findByUserId($userId): Collection
    {
        $userPlants = UserPlant::where('user_id', $userId)->get();
    
        if ($userPlants->isEmpty()) {
            return collect();
        }
    
        return $userPlants;
    }
}