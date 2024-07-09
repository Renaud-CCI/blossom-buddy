<?php
namespace App\Http\Controllers;

use App\Models\UserPlant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Http\JsonResponse;

class UserPlantController extends Controller
{
    /**
     * @OA\Post(
     *     path="/user/plant",
     *     summary="Ajoute une plante à la liste des plantes d'un utilisateur",
     *     tags={"UserPlant"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "plant_id"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="plant_id", type="integer", example=2),
     *             @OA\Property(property="name", type="string", example="Rose", nullable=true),
     *             @OA\Property(property="city", type="string", example="Paris", nullable=true),
     *             @OA\Property(property="country", type="string", example="France", nullable=true),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plante ajoutée avec succès",
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Erreur inattendue",
     *     ),
     *     security={{ "sanctum": {} }},
     * )
     */
    public function store(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'city' => 'required|string',
            'country' => 'required|string',
        ]);

        $userPlant = new UserPlant();
        $userPlant->user_id = Auth::id();
        $userPlant->name = $validated['name'];
        $userPlant->city = $validated['city'];
        $userPlant->country = $validated['country'];
        $userPlant->save();

        return response()->json(['message' => 'Plante ajoutée avec succès'], 200);
    }

    /**
     * @OA\Delete(
     *     path="/user/plant/{id}",
     *     summary="Supprime une plante de la liste des plantes d'un utilisateur",
     *     tags={"UserPlant"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Plante supprimée avec succès",
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Erreur inattendue",
     *     ),
     *     security={{ "sanctum": {} }},
     * )
     */
    public function destroy($id): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $userPlant = UserPlant::where('id', $id)->where('user_id', Auth::id())->first();
        if (!$userPlant) {
            return response()->json(['error' => 'Plante non trouvée ou vous n\'avez pas la permission de la supprimer'], 404);
        }

        $userPlant->delete();

        return response()->json(null, 204);
    }
}
