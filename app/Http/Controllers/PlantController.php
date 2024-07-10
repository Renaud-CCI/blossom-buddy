<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use App\Models\Plant;
use App\Repositories\PlantRepositoryInterface;
use App\Repositories\PlantRepository;
use App\Services\PlantService;

class PlantController extends Controller
{
    private PlantRepositoryInterface $plantRepository;
    private PlantService $plantService;

    public function __construct(PlantRepository $plantRepository, PlantService $plantService)
    {
        $this->plantRepository = $plantRepository;
        $this->plantService = $plantService;
    }

    /**
     * Display a listing of the resource.
     * 
     * @OA\Get(
     *     path="/api/plant",
     *     summary="Display a listing of plants",
     *     tags={"Plants"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Plant")
     *         )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json($this->plantRepository->index());
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @OA\Post(
     *     path="/api/plant",
     *     summary="Store a newly created plant",
     *     tags={"Plants"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Plant")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Plant created",
     *         @OA\JsonContent(ref="#/components/schemas/Plant")
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $plant = $this->plantRepository->create($request->all());
        return response()->json($plant, 201);
    }

    /**
     * Display the specified resource.
     * 
     * @OA\Get(
     *     path="/api/plant/{name}",
     *     summary="Display the specified plant by name",
     *     tags={"Plants"},
     *     @OA\Parameter(
     *         name="name",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *     ),
     * )
     */
    public function showByName(string $name): JsonResponse
    {
        $plant = $this->plantRepository->findByName($name);
        if (!$plant) {
            return response()->json(['error' => 'Plant not found'], 404);
        }
        return response()->json($plant);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plant $plant)
    {
        $plant->update($request->all());
        return response()->json($plant, 200);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @OA\Delete(
     *     path="/api/plant/{id}",
     *     summary="Remove the specified plant from storage",
     *     tags={"Plants"},
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
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *     ),
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->plantRepository->delete($id);
        if (!$deleted) {
            return response()->json(['error' => 'Plant not found'], 404);
        }
        return response()->json(null, 204);
    }

    /**
     * Update the list of plants in the database.
     * 
     * @OA\Put(
     *     path="/api/plant/update",
     *     summary="Update the list of plants in the database",
     *     tags={"Plants"},
     *     @OA\Response(
     *         response=200,
     *         description="Plants updated",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Plants updated"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="An unexpected error occurred"
     *             )
     *         )
     *     )
     * )
     */
    public function updatePlants(): JsonResponse
    {
        try {
            $this->plantService->updatePlants();
            return response()->json(['message' => 'Plants updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
