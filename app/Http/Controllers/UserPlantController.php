<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Http\JsonResponse;
use DateTime;
use App\Models\UserPlant;
use App\Models\Plant;
use App\Interfaces\IPlantRepository;
use App\Repositories\PlantRepository;
use App\Interfaces\IUserPlantRepository;
use App\Repositories\UserPlantRepository;
use App\Services\WeatherService;
use App\Services\VarDumpService;
use App\Services\WateringService;

class UserPlantController extends Controller
{
    private IPlantRepository $plantRepository;
    private IUserPlantRepository $userPlantRepository;
    private WeatherService $weatherService;
    private VarDumpService $varDumpService;
    private WateringService $wateringService;

    public function __construct(IPlantRepository $plantRepository, IUserPlantRepository $userPlantRepository, WeatherService $weatherService, VarDumpService $varDumpService, WateringService $wateringService)
    {
        $this->plantRepository = $plantRepository;
        $this->userPlantRepository = $userPlantRepository;
        $this->weatherService = $weatherService;
        $this->varDumpService = $varDumpService;
        $this->wateringService = $wateringService;
    }

    /**
     * @OA\Post(
     *     path="/user/plant",
     *     summary="Store a newly created plant for the user",
     *     tags={"UserPlant"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserPlant")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="UserPlant déjà enregistrée",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="UserPlant déjà enregistrée"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cette plante est inconnue",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cette plante est inconnue"),
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
            'plant_name' => 'required|string',
            'plant_title' => 'required|string',
            'last_watering' => 'required|date',
            'city' => 'required|string',
            'is_outdoor' => 'required|boolean',
        ]);
    
        $plant = $this->plantRepository->findByName($validated['plant_name']);
    
        if (!$plant) {
            return response()->json(['message' => 'Cette plante est inconnue'], 404);
        }
    
        $result = $this->userPlantRepository->findOrCreate([
            'user_id' => Auth::id(),
            'plant_id' => $plant->id,
            'plant_name' => $validated['plant_name'],
            'plant_title' => $validated['plant_title'],
            'last_watering' => $validated['last_watering'],
            'city' => $validated['city'],
            'is_outdoor' => $validated['is_outdoor'],
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

    /**
     * @OA\Get(
     *     path="/api/user/plant/watering/{userPlantId}",
     *     summary="Détermine si une plante donnée doit être arrosée",
     *     description="Renvoie si l'utilisateur doit arroser sa plante basé sur le dernier arrosage, le type de plante, et les conditions météorologiques si la plante est à l'extérieur.",
     *     operationId="shouldWatering",
     *     tags={"UserPlant"},
     *     @OA\Parameter(
     *         name="userPlantId",
     *         in="path",
     *         description="ID de la plante de l'utilisateur",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Réponse avec la décision d'arroser ou non la plante",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 description="Message indiquant si la plante doit être arrosée ou non"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plante non trouvée"
     *     )
     * )
     */
    public function shouldWatering(int $userPlantId): JsonResponse
    {
        $userPlant = $this->userPlantRepository->findById($userPlantId);
        
        if (!$userPlant) {
            return response()->json(['message' => 'Plante non trouvée'], 404);
        }

        return $this->wateringService->wateringStrategy($userPlant);
    }

    public function testCityWeather(string $city): JsonResponse
    {
        $weather = $this->weatherService->showCityWeather($city);
        return $weather;
    }
}