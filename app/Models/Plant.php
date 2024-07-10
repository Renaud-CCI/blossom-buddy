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
 *     ),
 *     @OA\Property(
 *         property="image",
 *         type="string",
 *         title="Image URL",
 *         description="URL of the plant image",
 *         example="https://example.com/images/ficus.jpg"
 *     )
 * )
 */
class Plant extends Model implements PlantInterface
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['common_name', 'watering_general_benchmark', 'image'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'watering_general_benchmark' => 'array',
    ];

    /**
     * The users that belong to the plant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_plant');
    }
}