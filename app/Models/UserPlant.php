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
 *         property="name",
 *         type="string",
 *         description="Name of the plant",
 *         example="Rose"
 *     ),
 *     @OA\Property(
 *         property="city",
 *         type="string",
 *         description="City where the plant is located",
 *         example="Paris"
 *     ),
 * )
 */
class UserPlant extends Model
{
    use HasFactory;

    protected $table = 'user_plant';
    public $timestamps = true;
    protected $fillable = ['user_id', 'plant_id', 'name', 'city'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }
}