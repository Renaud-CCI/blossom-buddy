<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Plant;

/**
 * @OA\Schema(
 *     title="UserPlant",
 *     description="UserPlant model, representing the many-to-many relationship between users and plants with additional information",
 *     @OA\Xml(
 *         name="UserPlant"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         description="ID of the user",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="plant_id",
 *         type="integer",
 *         description="ID of the plant",
 *         example=2
 *     ),
 *     @OA\Property(
 *         property="plant_name",
 *         type="string",
 *         description="Name of the plant",
 *         example="Rose"
 *     ),
 *     @OA\Property(
 *         property="plant_title",
 *         type="string",
 *         description="Title of the plant",
 *         example="Queen of the Garden"
 *     ),
 *     @OA\Property(
 *         property="last_watering",
 *         type="string",
 *         format="date-time",
 *         description="The last date the plant was watered",
 *         example="2023-04-01T00:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="city",
 *         type="string",
 *         description="City where the plant is located",
 *         example="Paris"
 *     ),
 *    @OA\Property(
 *         property="is_outdoor",
 *         type="boolean",
 *         description="Indicates if the plant is located outdoors",
 *         example=true
 *     ),
 * )
 */
class UserPlant extends Model
{
    use HasFactory;

    protected $table = 'user_plant';
    public $timestamps = true;
    protected $fillable = ['user_id', 'plant_id', 'plant_name', 'plant_title', 'last_watering', 'city', 'is_outdoor'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }
}