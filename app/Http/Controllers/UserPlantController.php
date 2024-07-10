<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Http\JsonResponse;
use App\Models\UserPlant;
use App\Models\Plant;
use App\Repositories\PlantRepositoryInterface;
use App\Repositories\PlantRepository;
use App\Repositories\UserPlantRepositoryInterface;
use App\Repositories\UserPlantRepository;

class UserPlantController extends Controller
{
    private PlantRepositoryInterface $plantRepository;
    private UserPlantRepositoryInterface $userPlantRepository;

    public function __construct(PlantRepository $plantRepository, UserPlantRepository $userPlantRepository)
    {
        $this->plantRepository = $plantRepository;
        $this->userPlantRepository = $userPlantRepository;
    }

    /**
     * @OA\Post(
     *     path="/user/plant",
     *     summary="Store a newly created plant for the user",
     *     tags={"UserPlant"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "city"},
     *             @OA\Property(property="name", type="string", example="Rose"),
     *             @OA\Property(property="city", type="string", example="Paris")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plante ajoutée avec succès ou UserPlant déjà enregistrée",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Plante ajoutée avec succès"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Données invalides fournies",
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
        $validated = $request->validate([
            'name' => 'required|string',
            'city' => 'required|string',
        ]);

        $plant = $this->plantRepository->findByName($validated['name']);

        if (!$plant) {
            $plantData = [
                'common_name' => $validated['name'],
                'watering_general_benchmark' => json_encode([
                    'value' => rand(5, 7),
                    'unit' => 'days',
                ]),
            ];
            $plant = $this->plantRepository->create($plantData);
        }

        $result = $this->userPlantRepository->findOrCreate([
            'user_id' => Auth::id(),
            'plant_id' => $plant->id,
            'name' => $validated['name'],
            'city' => $validated['city'],
        ]);
    
        if ($result['created']) {
            return response()->json(['message' => 'Plante ajoutée avec succès'], 200);
        } else {
            return response()->json(['message' => 'UserPlant déjà enregistrée'], 200);
        }
    }

    /**
     * @OA\Get(
     *     path="/user/plants",
     *     summary="Récupère toutes les plantes possédées par un utilisateur",
     *     tags={"UserPlant"},
     *     security={{ "sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Opération réussie",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/UserPlant")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non autorisé"
     *     )
     * )
     */
    public function getUserPlants(): JsonResponse
    {
        $userId = Auth::id();
        $plants = $this->userPlantRepository->findByUserId($userId);

        if ($plants->isEmpty()) {
            return response()->json(['message' => 'Pas de données pour cet utilisateur'], 200);
        }

        return response()->json($plants, 200);
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
