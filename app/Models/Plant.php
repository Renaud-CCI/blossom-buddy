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
 *     ),
 *     @OA\Property(
 *         property="common_name",
 *         type="string",
 *         title="Common Name",
 *         description="Common name of the plant",
 *         example="Ficus"
 *     ),
 *     @OA\Property(
 *         property="watering_general_benchmark",
 *         type="object",
 *         description="General benchmark for watering the plant",
 *         @OA\Property(
 *             property="value",
 *             type="integer",
 *             example=5
 *         ),
 *         @OA\Property(
 *             property="unit",
 *             type="string",
 *             example="days"
 *         )
 *     )
 * )
 */
class Plant extends Model implements PlantInterface
{
    use HasFactory;

    /**
     * @var string
     */
    protected $fillable = ['common_name', 'watering_general_benchmark'];

    /**
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
