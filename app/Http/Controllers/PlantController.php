<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;

class PlantController extends Controller
{
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
        $plants = Plant::all();
        return response()->json($plants);
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
        $plant = Plant::create($request->all());
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
    public function showByName($name): JsonResponse
    {
        $plant = Plant::where('common_name', $name)->first();
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
        //
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
    public function destroy($id)
    {
        $plant = Plant::find($id);
        if (!$plant) {
            return response()->json(['error' => 'Plant not found'], 404);
        }
        $plant->delete();
        return response()->json(null, 204);
    }
}
