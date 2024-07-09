<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 *     title="User",
 *     description="User model",
 *     @OA\Xml(
 *         name="User"
 *     )
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * @OA\Property(
     *     title="Name",
     *     description="Name of the user",
     *     example="John Doe"
     * )
     *
     * @var string
     */
    /**
     * @OA\Property(
     *     title="Email",
     *     description="Email of the user",
     *     example="john.doe@example.com"
     * )
     *
     * @var string
     */
    /**
     * @OA\Property(
     *     title="Password",
     *     description="Password of the user",
     *     format="password",
     *     example="secret"
     * )
     *
     * @var string
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * @OA\Property(
     *     title="Hidden Attributes",
     *     description="Attributes that should be hidden for arrays",
     *     type="array",
     *     @OA\Items(type="string"),
     *     example={"password", "remember_token"}
     * )
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function plants()
    {
        return $this->belongsToMany(Plant::class, 'user_plant');
    }
}
