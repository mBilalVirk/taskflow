<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/teams",
     *     summary="List user's teams",
     *     tags={"Teams"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Teams retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Team"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index()
    {
        $teams = auth()->user()->teams()->get();
        return $this->success($teams, 'Teams retrieved');
    }

    /**
     * @OA\Post(
     *     path="/teams",
     *     summary="Create new team",
     *     tags={"Teams"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "slug"},
     *             @OA\Property(property="name", type="string", maxLength=255),
     *             @OA\Property(property="slug", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Team created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Team")
     *     ),
     *     @OA\Response(response=400, description="Validation error"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:teams',
            'description' => 'nullable|string',
        ]);

        $team = Team::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
        ]);

        $team->members()->attach(auth()->id(), ['role' => 'admin']);

        return $this->success($team, 'Team created', 201);
    }

    /**
     * @OA\Get(
     *     path="/teams/{id}",
     *     summary="Get team details",
     *     tags={"Teams"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Team details",
     *         @OA\JsonContent(ref="#/components/schemas/Team")
     *     ),
     *     @OA\Response(response=404, description="Team not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function show(Team $team)
    {
        return $this->success($team->load('members', 'projects'), 'Team retrieved');
    }

    /**
     * @OA\Put(
     *     path="/teams/{id}",
     *     summary="Update team",
     *     tags={"Teams"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Team updated"),
     *     @OA\Response(response=404, description="Team not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
        ]);

        $team->update($validated);

        return $this->success($team, 'Team updated');
    }

    /**
     * @OA\Delete(
     *     path="/teams/{id}",
     *     summary="Delete team",
     *     tags={"Teams"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(response=200, description="Team deleted"),
     *     @OA\Response(response=404, description="Team not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function destroy(Team $team)
    {
        $team->delete();
        return $this->success(null, 'Team deleted');
    }
}