<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Weather",
 *     description="Weather model",
 *     @OA\Xml(
 *         name="Weather"
 *     ),
 *     @OA\Property(
 *         property="city",
 *         type="string",
 *         description="Name of the city",
 *         example="Paris"
 *     ),
 *     @OA\Property(
 *         property="precipitations",
 *         type="object",
 *         description="Daily precipitation amounts",
 *         example={"2024-07-11": 7.4, "2024-07-12": 0.0, "2024-07-13": 5.4},
 *         @OA\AdditionalProperties(
 *             type="number",
 *             format="float"
 *         )
 *     )
 * )
 */
class Weather extends Model
{
    use HasFactory;

    /**
     *
     * @var string
     */
    private $city;

    /**
     * @var \stdClass
     */
    private $precipitations;

    protected $casts = [
        'precipitations' => 'json',
    ];

    protected $fillable = [
        'city',
        'precipitations',
    ];
}
