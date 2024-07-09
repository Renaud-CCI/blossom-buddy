<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\PlantInterface;

/**
 * @OA\Schema(
 *     title="Plant",
 *     description="Plant model",
 *     @OA\Xml(
 *         name="Plant"
 *     )
 * )
 */
class Plant extends Model implements PlantInterface
{
    use HasFactory;

    /**
     * @OA\Property(
     *     title="Common Name",
     *     description="Common name of the plant",
     *     example="Ficus"
     * )
     *
     * @var string
     */
    protected $fillable = ['common_name', 'watering_general_benchmark'];

    /**
     * @OA\Property(
     *     title="Watering General Benchmark",
     *     description="General benchmark for watering the plant",
     *     type="array",
     *     @OA\Items(
     *         type="string",
     *         example="Every 2 days"
     *     )
     * )
     *
     * @var array
     */
    protected $casts = [
        'watering_general_benchmark' => 'array',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_plant');
    }
}
