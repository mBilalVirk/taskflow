<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Models\Team;

class UserController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/users/me",
     *     summary="Get current user",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Current user data",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function me()
    {
        return $this->success(auth()->user(), 'Current user');
    }

    /**
     * @OA\Get(
     *     path="/teams/{id}/members",
     *     summary="Get team members",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Team members retrieved",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/User"))
     *         )
     *     ),
     *     @OA\Response(response=404, description="Team not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function teamMembers(Team $team)
    {
        $members = $team->members()->get();
        return $this->success($members, 'Team members retrieved');
    }
}